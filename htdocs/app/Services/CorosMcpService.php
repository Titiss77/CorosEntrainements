<?php

namespace App\Services;

/**
 * Point d'intégration du client MCP COROS.
 *
 * COROS MCP est un serveur MCP protégé par OAuth 2.0.
 * Cette classe n'envoie volontairement aucune requête non authentifiée
 * et ne simule pas de données COROS.
 *
 * Documentation :
 * https://support.coros.com/hc/fr/articles/53181619102996-D%C3%A9velopper-sur-COROS-MCP
 */
class CorosMcpService
{
    private string $endpoint;

    public function __construct()
    {
        $this->endpoint = (string) (env('COROS_MCP_ENDPOINT') ?: 'https://mcp.coros.com/mcp');
    }

    /**
     * À relier à un client MCP Streamable HTTP authentifié OAuth 2.0.
     *
     * Flux attendu :
     * 1. découverte des métadonnées OAuth du serveur MCP ;
     * 2. autorisation OAuth avec state et PKCE ;
     * 3. conservation sécurisée du jeton ;
     * 4. appel MCP tools/list puis tools/call sur querySportRecords ;
     * 5. appels getActivityDetail pour enrichir les résultats ;
     * 6. normalisation des données et ActivityModel::upsertFromCoros().
     */
    public function syncRecentActivities(): int
    {
        throw new \RuntimeException(
            'Client OAuth 2.0/MCP non configuré. Aucun appel COROS n’a été effectué.'
        );
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }
}
