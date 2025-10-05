<?php

namespace Rede;

use stdClass;

class Environment implements RedeSerializable
{
    public const PRODUCTION = 'https://api.userede.com.br/erede';
    public const SANDBOX = 'https://api.userede.com.br/desenvolvedores';
    public const VERSION = 'v1';

    /**
     * OAuth2 token endpoints
     */
    public const OAUTH_TOKEN_PRODUCTION = 'https://api.userede.com.br/redelabs/oauth2/token';
    public const OAUTH_TOKEN_SANDBOX = 'https://rl7-sandbox-api.useredecloud.com.br/oauth2/token';

    /**
     * @var string|null
     */
    private ?string $ip = null;

    /**
     * @var string|null
     */
    private ?string $sessionId = null;

    /**
     * @var string
     */
    private string $endpoint;

    /**
     * @var string OAuth2 token endpoint URL
     */
    private string $oauthTokenUrl;

    /**
     * Creates an environment with its base url and version
     *
     * @param string $baseUrl
     */
    private function __construct(string $baseUrl)
    {
        $this->endpoint = sprintf('%s/%s/', $baseUrl, Environment::VERSION);

        if ($baseUrl === Environment::PRODUCTION) {
            $this->oauthTokenUrl = Environment::OAUTH_TOKEN_PRODUCTION;
        } elseif ($baseUrl === Environment::SANDBOX) {
            $this->oauthTokenUrl = Environment::OAUTH_TOKEN_SANDBOX;
        } else {
            $this->oauthTokenUrl = rtrim($baseUrl, '/') . '/oauth2/token';
        }
    }

    /**
     * @return Environment A preconfigured production environment
     */
    public static function production(): Environment
    {
        return new Environment(Environment::PRODUCTION);
    }

    /**
     * @return Environment A preconfigured sandbox environment
     */
    public static function sandbox(): Environment
    {
        return new Environment(Environment::SANDBOX);
    }

    /**
     * @param string $service
     *
     * @return string Gets the environment endpoint
     */
    public function getEndpoint(string $service): string
    {
        return $this->endpoint . $service;
    }

    /**
     * @return string OAuth2 token endpoint URL
     */
    public function getOAuthTokenUrl(): string
    {
        return $this->oauthTokenUrl;
    }

    /**
     * @param string $oauthTokenUrl
     * @return $this
     */
    public function setOAuthTokenUrl(string $oauthTokenUrl): static
    {
        $this->oauthTokenUrl = $oauthTokenUrl;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return $this
     */
    public function setIp(string $ip): static
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     *
     * @return $this
     */
    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function jsonSerialize(): mixed
    {
        $consumer = new stdClass();
        $consumer->ip = $this->ip;
        $consumer->sessionId = $this->sessionId;

        return ['consumer' => $consumer];
    }
}
