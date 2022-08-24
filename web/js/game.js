'use strict';

class Game {
    current = [1, 1];
    status = false;

    next = function() {
        if (this.current[1] < 5) {
            this.current[1]++;
            return this.current;
        } else {
            this.status = true;
            return this.current;
        }
    }

    prev = function() {
        if (this.current[1] > 1) {
            this.current[1]--;
            return this.current;
        } else {
            return this.current;
        }
    }

    save = function() {

    }
}

const game = new Game();

const letters = document.querySelectorAll('.letter');
letters.forEach (letter => {
    letter.addEventListener('click', (event) => {
        if (game.current[1]) {
            const cell = document.getElementById(game.current.join(''));
            if (game.status == false) {
                cell.value = event.target.innerText;
            }
        }
        game.next();
    })
});

const back = document.querySelector('.back');
back.addEventListener('click', (event) => {
    let cell = document.getElementById(game.current.join(''));
    if (cell.value == '') {
        game.prev()
        cell = document.getElementById(game.current.join(''));
        cell.value = '';
    } else {
        cell.value = '';
        game.status = false;
    }
});

const save = document.querySelector('.save-button');
save.addEventListener('click', (event) => {
    if (game.status == false) return;

    let word = '';
    for (let i = 1; i <= 5; i++) {
        let cell = document.getElementById([game.current[0], i].join(''));
        word += cell.value;
    }

    checkWord(word.toLowerCase())
    .then((resp) => {
        console.log(resp['result']);
        if (resp['result']) {
            let word = '';
            for (let i = 1; i <= 5; i++) {
                let cell = document.getElementById([game.current[0], i].join(''));
                let letter = document.getElementById(cell.value);
                if (answer[i-1] == cell.value) {
                    cell.classList.add('find');
                    letter.classList.add('find');
                };
                if (answer.indexOf(cell.value) != -1) {
                    cell.classList.add('good');
                    letter.classList.add('good');
                }
                cell.classList.add('checked');
                letter.classList.add('checked');
                word += cell.value;
            }

            if (word == answer.join('')) {
                alert('Игра окончена, вы выиграли');
                game.current = null;
                return;
            }

            if (game.current[0] == 6) {
                alert('GAME OVER LOOSER :D\nМы загадали слово ' + answer.join(''));
            } else {
                game.current[0]++;
                game.current[1] = 1;
                game.status = false;
            }
        } else {
            confirm('Нет такого слова, сходи за словарем');
            return;
        }
    })

    
});

async function checkWord(word) {
    let response = await fetch('index.php?r=game/check&word=' + word);
    let result = await response.json();
    return result;
}


// async function init() {
//     while (true) {
//         let answer = prompt('Загадайте слово', '').toLowerCase();
//         let check = await checkWord(answer).then(());
//         if (check['result']) {
//             answer = answer.toUpperCase().split('');
//             break;
//         }
//         confirm('Введите другое слово из 5 букв');
//     }

//     globalThis.answer = answer;
// }

// init();

async function getWord() {
    let params = (new URL(document.location)).searchParams;
    globalThis.answer = '';

    if (params.get("key")) {
        $.post( window.location.origin + "/index.php?r=game/continue-game", {key: params.get("key")})
        .done(function(data) {
            data = JSON.parse(data);
            if (data.result) {
                globalThis.answer = data.result.answer.toUpperCase().split('');
                console.log(globalThis.answer);
            } else {
                window.location.replace(window.location.origin + "/index.php");
            }
        })
        .fail(function() {
            alert( "error" );
            window.location.replace(window.location.origin + "/index.php");
        })
    } else {
        let userAnswer = prompt('Загадайте слово', '').toLowerCase();
        userAnswer = userAnswer.replace('ё', 'е');
        checkWord(userAnswer)
        .then((resp) => {
            if (resp['result']) {
                globalThis.answer = userAnswer.toUpperCase().split('');
                return;
            } else {
                confirm('Введите другое слово из 5 букв');
                getWord();
            }
        });
    }
}

// async function getWord() {
//     let response = await fetch('index.php?r=game/start-solo-game');
//     let result = await response.json();

//     let userAnswer = prompt('Загадайте слово', '').toLowerCase();
//     userAnswer = userAnswer.replace('ё', 'е');
//     checkWord(userAnswer)
//     .then((resp) => {
//         if (resp['result']) {
//             globalThis.answer = userAnswer.toUpperCase().split('');
//             return;
//         } else {
//             confirm('Введите другое слово из 5 букв');
//             getWord();
//         }
//     });
// }


getWord();