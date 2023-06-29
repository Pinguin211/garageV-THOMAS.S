<?php

try {
    $appSecret = bin2hex(random_bytes(32));
} catch (Exception $e) {
    echo 'Erreur : ressayer';
}

file_put_contents(
    ".env",
    "APP_ENV=prod\nAPP_SECRET=$appSecret"
);

echo "Fichier .env à était ajouté au projet\n";