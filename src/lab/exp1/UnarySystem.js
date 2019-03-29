function restrictInput(e) {
  this.value = this.value.replace(/[^1]+/, '');
}

function setupInput() {
  let inputs = document.getElementsByTagName("textarea");

  for(let i = 0; i < inputs.length; i++)
    inputs[i].addEventListener('input', restrictInput);
}

function addNumbers() {
  let num1 = document.getElementById("num1"),
    num2 = document.getElementById("num2"),
    result = document.getElementById("result");

  num1 = num1.value;
  num2 = num2.value;

  let answer = num1 + num2;

  if(!answer)
    answer = "0";

  result.textContent = `The sum of given numbers is ${ answer } [concatenation of both the numbers]`;
}

function mulNumbers() {
  let num1 = document.getElementById("num1"),
    num2 = document.getElementById("num2"),
    result = document.getElementById("result");

  num1 = num1.value.length;
  num2 = num2.value.length;

  let answerLength = num1 * num2,
    answer = '';

  for(let i = 0; i < answerLength; i++)
    answer += '1';

  if(!answer)
    answer = "0";

  result.textContent = `The product of given numbers is ${ answer } [1 repeated ${ num1 } * ${ num2 } times]`;
}

function setupHandlers() {
  let addButton = document.getElementById("add_button"),
      mulButton = document.getElementById("mul_button");

  addButton.addEventListener('click', addNumbers);
  mulButton.addEventListener('click', mulNumbers);
}

function main() {
  setupInput();
  setupHandlers();
}

main();

