<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eintrag löschen</title>
</head>
<body>
    <ul id="myList"></ul>
    <button id="addBtn">Eintrag hinzufügen</button>
    <button id="deleteBtn">Letzten Eintrag löschen</button>

    <script>
        let count = 1;
        document.getElementById('addBtn').addEventListener('click', function() {
            let li = document.createElement('li');
            li.textContent = 'Listenelement ' + count;
            count++;
            document.getElementById('myList').appendChild(li);
        });

        document.getElementById('deleteBtn').addEventListener('click', function() {
            let list = document.getElementById('myList');
            // Entfernt den letzten Eintrag, wenn die Liste nicht leer ist
            if (list.lastElementChild) {
                list.removeChild(list.lastElementChild);
            }
        });
    </script>
</body>
</html>
