<?php

1. Инициализация репозитория
    git init
2. Выяснить текущий статус git
git status

Проект обычно создается в несколько "веток", которые потом
 объединяются в одну

 Мы получаем сообщение, что пока нет изменений (версий) и есть
 untracker files - файл, за которым git не следит

 3. Добавляем файл для отслеживания git add git.php
 Теперь git status показывает, что он следит за файлом git.php

 Чтобы удалить файл от слежки, нужно прописать 

 git rm --cached git.php
 
 Для добавления всех файлов в папке можно прописать git add . (с точкой)

 4. При изменениях стоит фиксировать изменения командой git add (файл)
 5. Добавляем коммит (типа подтверждения версии проги, с которым можно работать)
        команда git commit -m "message"
 6. Для исключения файлов для добавления в репо можно его исключить:
    создаем файл .gitignore и пишем в него название файла для игнора
    и далее еще раз пишем git add . чтобы сам файл gitignore попал в список

    При работе с папками, при добавлении новых папок, можно игнорить целые папки,
    для этого пишем /папка в .gitignore
<<<<<<< HEAD
7.  Для слияния веток - команда git merge имя_ветки
=======
7. Для просмотра веток - команда git branch
    Чтобы добавить новую ветку - git branch название_ветки
    Чтобы переключиться на ветку git checkout название_ветки
        или создать и переключиться git checkout -b имя_новой_ветки 
    Для удаления ветки git branch -D test
    
    

>>>>>>> newBranch

