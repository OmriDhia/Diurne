<?php

namespace App\User\Security;

use stdClass;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthService
{
    public function __construct(private readonly RequestStack $requestStack, private $jwtSecretKey) {}

    public const JWT_ALG = 'HS256';
    public const SECONDS_VALID = 1440 * 60;

    /**
     * Generate JWT token based on given user-data.
     *
     * @param array $userData Array which contains relevant user-data to include into JWT payload
     *
     * @return string JWT token required to gain access to restricted rest api methods
     */
    public function authenticate(array $userData)
    {
        return $this->_generateJWT($userData);
    }

    /**
     * Generate JWT token based on given user-data.
     *
     * @return string
     */
    private function _generateJWT(array $userData)
    {
        $issuedAt = time();
        $secondsValid = self::SECONDS_VALID;
        $expirationTime = $issuedAt + $secondsValid; // jwt valid for $secondsValid seconds from the issued time
        $payload = [
            'sub' => $userData['email'],
            'email' => $userData['email'],
            'iat' => $issuedAt,
            'exp' => $expirationTime,
        ];
        $key = $this->jwtSecretKey;
        $alg = self::JWT_ALG;
        $jwt = JWT::encode($payload, $key, $alg);

        return $jwt;
    }

    /**
     * Check if request is authenticated.
     *
     * @return bool true if is authenticated, false otherwise
     */
    public function isAuthenticated()
    {
        $token = $this->_getBearerToken();

        $decoded_array = $this->_validateJWT($token);
        if (!empty($decoded_array)) {
            // process valid token
            return true;
        }

        return false;
    }

    /**
     * Get decoded authentication token for the currently authenticated user.
     *
     * @return mixed array of decoded jwt claims or false
     */
    public function getDecodedAuthToken()
    {
        $token = $this->_getBearerToken();
        $decoded_array = $this->_validateJWT($token);
        if (!empty($decoded_array)) {
            return $decoded_array;
        } else {
            return false;
        }
    }

    /**
     * Get access token from header.
     *
     * @return string|null Request header information (if exists) or null
     */
    private function _getBearerToken(): ?string
    {
        $request = $this->requestStack->getCurrentRequest();
        $authHeader = $request->headers->get('Authorization');
        if (str_contains((string) $authHeader, 'Bearer ')) {
            $token = explode(' ', (string) $authHeader);
            if (isset($token[1])) {
                return $token[1]; // actual token
            }
        }

        return null;
    }

    /**
     * Check if jwt token is valid.
     *
     * @param string $token
     *
     * @return array|null decoded array if is-valid-wt, null otherwise
     */
    private function _validateJWT($token): array|null
    {
        if (!$token) {
            return null;
        }
        try {
            $key = $this->jwtSecretKey;
            JWT::$leeway = 1440; // $leeway in seconds

            $headers = new stdClass();
            $decoded = JWT::decode($token, new Key($key, self::JWT_ALG), $headers);
            $decoded_array = (array) $decoded;
        } catch (Exception) {
            $decoded_array = null;
        }

        return $decoded_array;
    }

    public function getToken(): string|null
    {
        return $this->_getBearerToken();
    }

    /**
     * @return string[]
     *
     * @psalm-return list{'app.swagger_ui', 'authenticate_user', 'register_user', 'coop_tilleuls_forgot_password.reset', 'coop_tilleuls_forgot_password.update', 'coop_tilleuls_forgot_password.get_token', 'redirect_home'}
     */
    public function excludedRoutes(): array
    {
        return [
            'app.swagger_ui',
            'authenticate_user',
            'register_user',
            'coop_tilleuls_forgot_password.reset',
            'coop_tilleuls_forgot_password.update',
            'coop_tilleuls_forgot_password.get_token',
            'redirect_home',
        ];
    }
}
