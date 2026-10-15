<?php

namespace App\Services;

use App\Models\ActivityModel;

class CorosMcpService
{
    private string $endpoint;
    private string $accessToken;

    public function __construct()
    {
        $this->endpoint = (string) (env('COROS_MCP_ENDPOINT') ?: 'https://mcp.coros.com/mcp');
        $this->accessToken = (string) session()->get('coros_access_token');
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Interroge le serveur MCP pour récupérer les activités et les enregistre.
     */
    public function syncRecentActivities(): int
    {
        if (empty($this->accessToken)) {
            throw new \RuntimeException('Jeton d\'accès manquant. Veuillez vous connecter à COROS.');
        }

        $client = \Config\Services::curlrequest();
        $importedCount = 0;

        // Appel MCP : Exemple avec tools/call pour querySportRecords
        // La structure exacte dépend de la documentation MCP de COROS
        $payload = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'method' => 'tools/call',
            'params' => [
                'name' => 'querySportRecords',
                'arguments' => [
                    'limit' => 10 // On récupère les 10 dernières activités
                ]
            ]
        ];

        try {
            $response = $client->post($this->endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => $payload,
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                
                // Extraction des résultats MCP (à adapter selon la structure de retour de COROS)
                $activities = $data['result']['activities'] ?? [];
                
                $activityModel = new ActivityModel();

                foreach ($activities as $corosActivity) {
                    // Normalisation des données pour votre base de données
                    $activityData = [
                        'coros_activity_id'       => $corosActivity['id'] ?? null,
                        'name'                    => $corosActivity['name'] ?? 'Activité COROS',
                        'sport'                   => $corosActivity['sportType'] ?? 'Inconnu',
                        'started_at'              => date('Y-m-d H:i:s', $corosActivity['startTime']), // Si c'est un timestamp
                        'duration_seconds'        => $corosActivity['duration'] ?? 0,
                        'distance_meters'         => $corosActivity['distance'] ?? 0,
                        'avg_heart_rate'          => $corosActivity['avgHeartRate'] ?? null,
                        'max_heart_rate'          => $corosActivity['maxHeartRate'] ?? null,
                        'elevation_gain_m'        => $corosActivity['elevationGain'] ?? null,
                        'avg_speed_mps'           => $corosActivity['avgSpeed'] ?? null,
                        'raw_data'                => json_encode($corosActivity)
                    ];

                    // Sauvegarde dans la base de données
                    if ($activityModel->upsertFromCoros($activityData)) {
                        $importedCount++;
                    }
                }
            } else {
                throw new \RuntimeException('Erreur API COROS : ' . $response->getBody());
            }

        } catch (\Exception $e) {
            throw new \RuntimeException('Erreur de communication MCP : ' . $e->getMessage());
        }

        return $importedCount;
    }
}