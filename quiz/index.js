const quiz = [
	{
		question: 'ゲーム市場最も売れたゲーム機は？',
		answers: [
			'スーパーファミコン', 
			'プレステ2', 
			'ニンテンドースイッチ', 
			'ニンテンドーDS'
		],
		correct:'ニンテンドーDS'
	},{
		question: '糸井重里が企画に携わった、ニンテンドーの看板ゲームは？',
		answers: [
			'MOTHER2', 
			'スーパーマリオブラザーズ3', 
			'スーパードンキーコング', 
			'星のカービィ'
		],
		correct:'MOTHER2'
	},{
		question: 'ファイナルファンタジーⅣの主人公は？',
		answers: [
			'フリオニール', 
			'クラウド', 
			'ティーダ', 
			'セシル'
		],
		correct:'セシル'
	}

];

const quizLength = quiz.length;
let quizIndex = 0;
let score =0;



const $button = document.getElementsByTagName('button');
const buttonLength = $button.length;

const setuoQuiz = () =>{
	document.getElementById('js-question').textContent = quiz[quizIndex].question;
	let buttonIndex = 0;
	let buttonLength = $button.length;
	while(buttonIndex < buttonLength){
		$button[buttonIndex].textContent = quiz[quizIndex].answers[buttonIndex];
		buttonIndex++;
	}
}

setuoQuiz();

const clickHandler = (e) =>{
	if(quiz[quizIndex].correct === e.target.textContent){
		window.alert('正解!');
		score++;
	}else{
		window.alert('不正解!');
	}

	quizIndex++;

	if(quizIndex < quizLength){
		setuoQuiz();
	}else{
		window.alert('終了!あなたの正解数'+ score +'/' + quizLength + 'です！');
	}
};


let handlerIndex = 0;


while(handlerIndex < buttonLength){

	$button[handlerIndex].addEventListener('click', (e) => {
		clickHandler(e);
	});
	handlerIndex++;
}


