<?php namespace App\Services;

use App\Models\ActivityModel;
use adriangibbons\phpFITFileAnalysis; // La bibliothèque installée via Composer

class FitImportService
{
    public function importFile(string $filePath): bool
    {
        // 1. Initialiser le parseur sur le fichier
        // L'option 'data_mesgs' permet d'extraire les messages de données (session, record, etc.)
        $fit = new phpFITFileAnalysis($filePath, ['data_mesgs' => ['session']]);

        // 2. Vérifier si des données de session globale existent
        if (empty($fit->data_mesgs['session'])) {
            throw new \Exception("Aucun résumé de session n'a été trouvé dans ce fichier FIT.");
        }

        // On prend le dernier résumé de la session (souvent unique)
        $session = end($fit->data_mesgs['session']);

        // 3. Extraction des métriques
        $duration      = $session['total_timer_time'] ?? 0;
        $distance      = $session['total_distance'] ?? 0; // en mètres
        $avgHeartRate  = $session['avg_heart_rate'] ?? null;
        $maxHeartRate  = $session['max_heart_rate'] ?? null;
        $elevationGain = $session['total_ascent'] ?? null; // en mètres
        $avgSpeed      = $session['avg_speed'] ?? null; // en m/s

        // 4. Déterminer la date et le sport
        // Le format FIT stocke souvent le timestamp de début
        $startTime = $session['start_time'] ?? time();
        $date = date('Y-m-d H:i:s', $startTime);

        // FIT utilise des ID entiers pour les sports (1 = Run, 2 = Bike, 5 = Swim...)
        $sportId = $session['sport'] ?? 0;
        $sportName = $this->mapFitSport($sportId);

        // On génère un ID unique "maison" pour éviter les doublons si on ré-importe le même fichier
        $corosId = 'FIT_' . $startTime . '_' . (int)$distance;

        // 5. Préparation des données pour la BDD
        $activityData = [
            'coros_activity_id'       => $corosId,
            'name'                    => 'Import Manuel FIT',
            'sport'                   => $sportName,
            'started_at'              => $date,
            'duration_seconds'        => $duration,
            'distance_meters'         => $distance,
            'avg_heart_rate'          => $avgHeartRate,
            'max_heart_rate'          => $maxHeartRate,
            'elevation_gain_m'        => $elevationGain,
            'avg_speed_mps'           => $avgSpeed,
            'raw_data'                => json_encode(['source' => 'FIT Import', 'original_sport_id' => $sportId])
        ];

        // 6. Sauvegarde en BDD
        $model = new ActivityModel();
        return $model->upsertFromCoros($activityData);
    }

    /**
     * Convertit l'ID du sport du format FIT vers un format lisible.
     */
    private function mapFitSport(int $sportId): string
    {
        $map = [
            0 => 'Generic',
            1 => 'Run',
            2 => 'Bike',
            3 => 'Transition',
            4 => 'Fitness Equipment',
            5 => 'Swim',
            6 => 'Basketball',
            8 => 'Walking',
            11 => 'Flying',
            17 => 'Hiking',
            // Ajoutez d'autres sports ici une fois que vous connaîtrez leurs ID
        ];

        // Si le sport n'est pas dans la liste, on affiche "Inconnu (ID)" pour pouvoir l'ajouter ensuite
        return $map[$sportId] ?? 'Inconnu (' . $sportId . ')';
    }
}