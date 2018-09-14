# lib-user-auth-oauth2

Adala module yang memungkinkan request dari user diautentikasi berdasarkan method
OAuth2.

## Instalasi

Jalankan perintah di bawah di folder aplikasi:

```
mim app install lib-user-auth-oauth2
```

## Konfigurasi

Tambahkan konfigurasi seperti di bawah pada aplikasi:

```php
return [
    'libUserAuthOauth2' => [
        'loginRoute' => 'siteLogin',
        
        // access token lifetime
        'tokenLifetime' => 3600,

        // refresh token lifetime
        'refreshTokenLifetime' => 2592000,

        'methods' => [
            // Authorization Code
            'authorization_code' => true,

            // Implicit
            'implicit' => true,

            // User Credentials
            'password' => true,

            // Client Credentials
            'client_credentials' => true,

            // Refresh Token
            'refresh_token' => true
        ]
    ]
];
```

Opsi `loginRoute` akan digunakan oleh system ketika suatu aplikasi meminta authorize
sementara user belum login di browser tersebut.

## Penggunaan

Untuk metode autentikasi pada client, silahkan lihat source code yang tersimpa di folder
`example` di repository module ini.

Secara umum, module ini membuka dua endpoint, yaitu 
`APIHOST/auth/oauth2/authorize`, `APIHOST/auth/oauth2/token`, dan `APIHOST/auth/oauth2/revoke`.

## Lisensi

Module ini mengunakan library [bshaffer/oauth2-server-php-docs](https://github.com/bshaffer/oauth2-server-php-docs).
Silahkan mengacup ada library tersebut untuk urusan lisensi.