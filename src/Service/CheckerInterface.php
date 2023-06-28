<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CheckerInterface
{
    public function __construct(private CsrfTokenManagerInterface $tokenManager,
                                private Security $security,
                                private SessionInterface $session)
    {
    }

    /**
     * Vérifie si le fichier correspond au critère attendu
     */
    public function checkUploadedFile(array $file, int $maxSize, array $extensions, array $mimeType): bool
    {
        if (!$this->checkData($file, 'array', ['name', 'full_path', 'type', 'tmp_name', 'error', 'size']) ||
            $file['error'] !== 0 ||
            $file['size'] > $maxSize ||
            !in_array($file['type'], $mimeType, true)
        )
            return false;
        $ext = $this->fileGetExtension($file);
        if (!in_array($ext, $extensions))
            return false;
        return true;
    }

    public function fileGetExtension(array $file): string
    {
        $arr = explode('.', $file['name']);
        return $arr[array_key_last($arr)];
    }

    /**
     * Verifier et peux reformater la string selon les regles de securité
     * @param string $input
     * @param int $max_len
     * @return bool
     */
    public function checkUserInput(string &$input, int $max_len): bool
    {
        if (strlen($input) > $max_len)
            return false;
        $input = strip_tags($input);
        return true;
    }

    /**
     * Verifie si la session correspond un utilisateur connecter et verifie si il a le rôle requi
     * @param string|null $min_role
     * @return UserInterface|bool
     */
    public function checkUser(string $min_role = null): UserInterface | bool
    {
        $user = $this->security->getUser();
        if (!$user || $min_role && !$this->security->isGranted($min_role))
            return false;
        else
            return $user;
    }

    /**
     * Verifie si le token envoyé lors de la requete correspond bien
     * @param string $csrf_token_id - id du token dans la requete POST
     * @return bool
     */
    public function checkCsrfToken(string $csrf_token_id = 'csrf_token'): bool
    {
        if (!isset($_POST[$csrf_token_id]) || !self::checkDataStatic($_POST[$csrf_token_id], 'string') ||
            !$this->tokenManager->isTokenValid(new CsrfToken($this->session->getId(), $_POST[$csrf_token_id])))
            return false;
        else {
            unset($_POST[$csrf_token_id]);
            return true;
        }
    }

    /**
     * @param array $arr - L'array
     * @param string $key - La clé à verifier
     * @param string $type - le type accordé ('int', 'string', 'array', 'numeric')
     * @param array $array_values - si c'est un array, verifie si les clés données ne sont pas NULL
     * @return bool
     */
    public function checkArrayData(array $arr, string $key, string $type, array $array_values = []): bool
    {
        return self::checkArrayDataStatic($arr, $key, $type, $array_values);
    }

    /**
     * @param mixed $data - La valeur à vérifié
     * @param string $type - le type accordé ('int', 'string', 'array', 'numeric', json(string))
     * @param array $array_values - si c'est un array, verifie si les clés données ne sont pas NULL
     * @return bool
     */
    public function checkData(mixed $data, string $type, array $array_values = []): bool
    {
        return self::checkDataStatic($data, $type, $array_values);
    }

    public function checkDataWithAcceptedValue(mixed $data, string $type, array $accepted_values, bool $strict = false): bool
    {
        if (!self::checkDataStatic($data, $type))
            return false;
        return in_array($data, $accepted_values, $strict);
    }

    public static function checkDataStatic(mixed $data, string $type, array $array_values = []): bool
    {
        if (empty($data))
            return false;
        switch ($type) {
            case 'int':
                if (!is_int($data))
                    return false;
                break;
            case 'string':
                if (!is_string($data))
                    return false;
                break;
            case 'numeric':
                if (!is_numeric($data))
                    return false;
                break;
            case 'array':
                if (!is_array($data) || !self::checkArray($data, $array_values))
                    return false;
                break;
            case 'json':
                if (!is_string($data) || !is_array(($arr = json_decode($data, true))) || !self::checkArray($arr, $array_values))
                    return false;
                break;
            default:
                return false;
        }
        return true;
    }

    public static function checkArray(array $arr, array $keys): bool
    {
        foreach ($keys as $key)
        {
            if (!isset($arr[$key]))
                return false;
        }
        return true;
    }


    public static function checkArrayDataStatic(array $arr, string $key, string $type, array $array_values = []): bool
    {
        if (isset($arr[$key]))
            return self::checkDataStatic($arr[$key], $type, $array_values);
        else
            return false;
    }
}