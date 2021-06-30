<?php


$str1 = "A story about blue elephant and about an eagle";
$str2 = 'blue elephant and about an eagle';

//1. strlen() Определение длины строки
echo strlen($str1);
echo '<br> после <br>';
echo chop(strlen($str1));

// ответ: 29

//2. strpos() находит в строке первый экземпляр заданной подстроки

    echo '<br>' . $str1;
    echo '<br>  В какой позиции впервые находится символ - story? - в ' . strpos($str1, 'story');

//3. strrpos() находит в строке последний экземпляр заданного символа

echo '<br>' . $str1;
echo '<br>  В какой позиции находится последний символ - t? - в ' . strrpos($str1, 't');

//4. str_replace() Функция str_replace( ) ищет в строке все вхождения заданной подстроки и
//  заменяет их новой подстрокой.

echo '<br>' . $str1;
echo '<br>  Заменяем слово about на with -  ' . str_replace('about', 'with', $str1);

//5. strstr() возвращает часть строки, начинающуюся с первого вхождения заданной подстроки
echo '<br>' . $str1;
echo '<br>  Строка начинается со слова  - about: ' . strstr($str2, 'about');

//6. substr()  Функция substr( ) возвращает часть строки, начинающуюся с заданной начальной позиции
//  и имеющую заданную длину.

echo '<br>' . $str1;
echo strpos($str1, 'el');
echo '<br>  Строка начинается с символа El - ' . substr($str1, strpos($str1, 'el'));
