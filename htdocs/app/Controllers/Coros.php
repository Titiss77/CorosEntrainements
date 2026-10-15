<?php

namespace App\Controllers;

use App\Services\CorosMcpService;
use CodeIgniter\HTTP\RedirectResponse;

class Coros extends BaseController
{
    public function connect(): RedirectResponse|string
    {
        $clientId = env('COROS_CLIENT_ID');
        $redirectUri = env('COROS_REDIRECT_URI');
        $authUrl = env('COROS_AUTH_URL');

        if (!$clientId || !$redirectUri || !$authUrl) {
            return view('coros/not_configured', [
                'title' => 'Connexion COROS',
                'message' => 'Les variables d\'environnement COROS ne sont pas configurées.',
            ]);
        }

        // Génération d'un state aléatoire pour la sécurité (prévention CSRF)
        $state = bin2hex(random_bytes(16));
        session()->set('coros_oauth_state', $state);

        // Construction de l'URL d'autorisation
        $query = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'state'         => $state,
            'scope'         => 'activity:read' // Remplacez par les scopes requis par COROS
        ]);

        return redirect()->to($authUrl . '?' . $query);
    }

    public function callback(): RedirectResponse|string
    {
        $code = $this->request->getGet('code');
        $state = $this->request->getGet('state');
        $savedState = session()->get('coros_oauth_state');

        // 1. Vérification de sécurité du state
        if (!$state || $state !== $savedState) {
            return view('coros/not_configured', [
                'title' => 'Erreur de sécurité',
                'message' => 'Le paramètre state est invalide ou expiré.',
            ]);
        }

        if (!$code) {
            return view('coros/not_configured', [
                'title' => 'Erreur d\'autorisation',
                'message' => 'Aucun code d\'autorisation n\'a été renvoyé par COROS.',
            ]);
        }

        // 2. Échange du code contre un Access Token
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->post(env('COROS_TOKEN_URL'), [
                'form_params' => [
                    'client_id'     => env('COROS_CLIENT_ID'),
                    'client_secret' => env('COROS_CLIENT_SECRET'),
                    'grant_type'    => 'authorization_code',
                    'code'          => $code,
                    'redirect_uri'  => env('COROS_REDIRECT_URI'),
                ],
                'http_errors' => false
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                
                // 3. Sauvegarde du token (en session pour cet exemple, mais idéalement en BDD)
                session()->set('coros_access_token', $data['access_token']);
                if (isset($data['refresh_token'])) {
                    session()->set('coros_refresh_token', $data['refresh_token']);
                }

                return redirect()->to(site_url('dashboard'))->with('success', 'Connexion à COROS réussie !');
            } else {
                log_message('error', 'Erreur Token COROS : ' . $response->getBody());
                return redirect()->to(site_url('dashboard'))->with('error', 'Impossible de récupérer le jeton d\'accès.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Erreur CURL COROS : ' . $e->getMessage());
            return redirect()->to(site_url('dashboard'))->with('error', 'Erreur de communication avec COROS.');
        }
    }

    public function sync(): RedirectResponse
    {
        // Vérifie si l'utilisateur est bien connecté avant de synchroniser
        if (!session()->has('coros_access_token')) {
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Vous devez d\'abord connecter votre compte COROS.');
        }

        $service = new CorosMcpService();
        try {
            $count = $service->syncRecentActivities();
            return redirect()->to(site_url('dashboard'))->with('success', "Synchronisation terminée. $count nouvelles activités importées.");
        } catch (\Throwable $e) {
            log_message('error', 'COROS sync: {message}', ['message' => $e->getMessage()]);
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Synchronisation impossible : ' . $e->getMessage());
        }
    }
}