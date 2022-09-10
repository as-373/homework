//アルバムデータの作成 配列
let album = [
  {src: '1.jpg', msg: '山道の緑が気持ちいい'},
  {src: '2.jpg', msg: '階段きつかった'},
  {src: '3.jpg', msg: '高尾山薬王院！'},
  {src: '4.jpg', msg: '帰りはロープウェイでスイスイ'},
  {src: '5.jpg', msg: '〆のお蕎麦です'}
];

let mainImage = document.createElement('img');
mainImage.setAttribute('src', album[0].src);
mainImage.setAttribute('alt', album[0].msg);

let mainMsg = document.createElement('p');
mainMsg.innerText = mainImage.alt;

let mainFlame = document.querySelector('#gallery .main');
mainFlame.insertBefore(mainImage, null);
mainFlame.insertBefore(mainMsg, null);

let thumbFlame = document.querySelector('#gallery .thumb');
for (let i = 0; i < album.length ;i++){
  let thumbImage = document.createElement('img');
  thumbImage.setAttribute('src', album[i].src);
  thumbImage.setAttribute('alt', album[i].msg);
  thumbFlame.insertBefore(thumbImage, null);
}

thumbFlame.addEventListener('click', function(event){
  if(event.target.src){
    mainImage.src = event.target.src;
    mainMsg.innerText = event.target.alt;
  }
})

const $elm = document.querySelector('.main');
const $trigger = $elm.getElementsByTagName('img');

console.log($trigger)

$trigger[0].addEventListener('click', (e) => clickHandler(e));



const clickHandler = (e) => {
  e.preventDefault();

  const $target = e.currentTarget;
  const $content = $target.nextElementSibling;


  if($content.style.display === 'inline-block'){
    $content.style.display= 'none';
  }else{
    $content.style.display = 'inline-block';
  }
};

