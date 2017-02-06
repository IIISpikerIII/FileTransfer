# FileTransfer
Test Project FileTransfer

##Quick start

```php
require_once 'Factory.php';    
use FileTransfer as FT;    
$factory = new FT\Factory();
    
try {
    $conn = $factory->getConnection('ftp', 'user', 'pass', 'host');
    $conn->upload(__DIR__.'/test.txt');
    $conn->download('test.txt');
} catch (Exception $e) {
  echo $e->getMessage();
}
   
try {
    $conn = $factory->getConnection('ssh', 'user', 'pass', 'host', 2222);
    $conn->cd('tst')
        ->download('dump.tar.gz')
        ->close();
} catch(Exception $e) {
    echo $e->getMessage();
}
```

##Methods List

```php
    public function download($path);
    
    public function upload($path);
    
    public function exec($cmd);
    
    public function pwd();
    
    public function cd($path);
    
    public function close();    

```
