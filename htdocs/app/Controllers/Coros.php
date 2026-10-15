<?php

namespace App\Controllers;

use App\Services\CorosMcpService;
use CodeIgniter\HTTP\RedirectResponse;

class Coros extends BaseController
{
    public function connect(): RedirectResponse|string
    {
        // Le flux OAuth nécessite un client OAuth/MCP configuré.
        // Ne redirige pas vers une URL d'autorisation inventée.
        return view('coros/not_configured', [
            'title' => 'Connexion COROS',
            'message' => 'Le client OAuth 2.0/MCP COROS doit être configuré avant de pouvoir lancer l’autorisation.',
        ]);
    }

    public function callback(): string
    {
        // À compléter avec validation du paramètre state, échange du code OAuth
        // et stockage sécurisé du token. Ne pas accepter de callback sans state.
        return view('coros/not_configured', [
            'title' => 'Retour COROS',
            'message' => 'Le callback OAuth n’est pas encore activé : configure d’abord le client OAuth/MCP.',
        ]);
    }

    public function sync(): RedirectResponse
    {
        $service = new CorosMcpService();

        try {
            $service->syncRecentActivities();
            return redirect()->to(site_url('dashboard'))->with('success', 'Synchronisation terminée.');
        } catch (\Throwable $e) {
            log_message('error', 'COROS sync: {message}', ['message' => $e->getMessage()]);
            return redirect()->to(site_url('dashboard'))
                ->with('error', 'Synchronisation impossible : le client OAuth/MCP COROS n’est pas encore configuré.');
        }
    }
}
