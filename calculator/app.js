const buttons = document.querySelectorAll('button');//ボタン要素の全て取得
const result = document.querySelector('#result');


let calcText = "";
function buttonPressed(e){
  const text = e.target.textContent;
 
  
  if(text === '='){
    calcText = eval(calcText);
    if(!calcText){
      calcText = 'error';
    }
  }
  else if(text === 'C'){
    calcText = '0';
  }

  else{
    if(calcText === '0'){
      calcText = text;
    }
    else{
      calcText += text;
    }
  }
  result.textContent = calcText;
  
}

buttons.forEach(button => button.addEventListener('click', buttonPressed));




