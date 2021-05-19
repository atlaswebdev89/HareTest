<?php

//Соединение с mysql используя PDO
try{
        $connect = new PDO ("mysql:host=localhost:8946;dbname=hare;charset=utf8", "user", "user");
            $connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);    
}catch (PDOException $e) {
        fwrite(STDOUT, $e->getMessage());
   exit;
}

//Указываем нужную кодировку
$collacation = "ALTER DATABASE ".getenv("DB_DB")." CHARACTER SET utf8 COLLATE utf8_general_ci";
$connect->exec($collacation);

//Удаляем таблицу
$connect->exec("DROP TABLE MyText");

    $sql = "CREATE TABLE MyText (
                    id INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    text LONGTEXT NOT NULL,
                    reg_date TIMESTAMP
            )";
$connect->exec($sql);

//Источник для текстов
$data = [
                "mushet.txt", 
                "text.txt",
                "voyna-i-mir-1.txt",
                "voyna-i-mir-2.txt",
                "voyna-i-mir-3.txt",
                "voyna-i-mir-4.txt",
                "white-klyk.txt",
                "vedmak.txt",
                "sezon-groz.txt",
                "nochnoy-dozor.txt",
                "mech-prednaznacheniya.txt",
                "krov-elfov.txt",
            ];


$stdout = fopen('php://stdout', 'w');

for ($count = 0; $count<=750000; $count++){
            if(!file_exists($file = __DIR__.'/'.$data[array_rand($data)])) {
                continue;
            }
    
         for ($start = 0; ; $count++) {
                    $length = (rand(3000, 5000))*2; //в байтах
                        $string = file_get_contents($file, FALSE, NULL, $start, $length);

                            if(mb_strlen(trim($string), 'UTF-8') != 0 && trim($string) != "." && mb_strlen(trim($string), 'UTF-8') > 3000) {
                                $end_symbol = strrpos($string, '.');

                                    if(!$end_symbol) {
                                            $end_symbol = strrpos($string, ',');
                                    }
                                    
                                    if(!$end_symbol) {
                                       $end_symbol = strrpos($string, ' ');  
                                    }

                                $string = trim(substr($string, 0, $end_symbol), ' .,');
                                
                                    //Добавление в БД
                                    $sql = "INSERT INTO `MyText` (text) VALUES (:text)";
                                        $params = [
                                            ":text" =>$string,
                                        ];
                                    $request = $connect->prepare($sql);
                                    $request->execute($params);

                            $start = $start+$end_symbol;

                            //Или STDOUT константу для php-cli

                                fwrite($stdout, $count."\n");

                            }else {
                                            fwrite($stdout, $count." Текст закончился!!\n") ;
                                        $count--;
                                 break;
                            }
                }
}



