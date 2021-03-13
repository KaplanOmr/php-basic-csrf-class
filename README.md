# PHP Basic CSRF Class
**Temel Düzey PHP CSRF Class yapısı.**

*Örnek kullanım `example.php`'de gösterilmiştir.*

Class kullanımı:

```php
$csrf = new CSRF();
```
Şeklinde tanımlanır. CSRF Classı isteğe başlı olarak 3 farklı parametre almaktır. Bunlar sırası ile:
```php
string $inputName // Özel İnput Name
bool $expiredToken // Token Zaman Aşımı
int $expriredTokenTime // Token Zaman Aşım Süresi
```
Fromdan gelen CSRF token'ı kontrol etmek için 2 farklı yöntem vardır:
```php
// 1. Fonksiyon
// Bu fonksiyon ile formdan gönderilen CSRF tokenini direkt kontrol edebilirsiniz.

$csrf->checkToken($_REQUEST['_csrf']); // Bu fonksiyon sizlere boollean değer döndürür.

// 2. Fonksiyon
// Bu fonksiyon ile gönderilen request dizisini direkt olarak fonksiyona göndererek kontrol sağlayabilirsiniz.

$csrf->checkRequest($_REQUEST); // Bu fonksiyon sizlere boollean değer döndürür.
```
Örnek kullanım:
```php
<h1>CSRF Example</h1>
<hr>

<?php
    require_once('csrf.class.php');

    $csrf = new CSRF();
    $csrfInput = $csrf->createInput();

    if($_REQUEST){
        if($csrf->checkToken($_GET['_csrf'])){
            echo '<span style="color:green">Success</span><hr>';
        }else{
            echo '<span style="color:red">Failed</span><hr>';
        }
    }
?>

<form action="" method="get">
    <?= $csrfInput ?>
    <input type="text" name="name">
    <br>
    <br>
    <input type="submit" value="send">
</form>
```
