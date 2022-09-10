
(() => {

const weeks = ['日','月','火','水','木','金','土']
const date = new Date() //現在の日付データの取得
let year = date.getFullYear()//年データの取得
let month = date.getMonth() + 1//月の取得
const showNum = 2


function showCalendar(year,month){
  for(let i = 0;  i < showNum; i++){
    const calendarHtml = createCalendar(year,month)
    const section = document.createElement('section')
    section.innerHTML = calendarHtml
    document.querySelector('#calendar').appendChild(section)

    month++
    if(month > 12){
      year++
      month = 1
    }
  }
}


function createCalendar(year,month) {

  const startDate = new Date(year, month - 1, 1)//月の初めの日
  const endDate = new Date(year, month, 0);//月の最後の日
  const endDayCount = endDate.getDate()
  const lastMonthendDate = new Date(year, month - 2 ,0)
  const lastMonthendDayCount = lastMonthendDate.getDate()
  const startDay = startDate.getDay()//最初の曜日を取得
  let dayCount = 1;
  let calendarHtml = ''

  calendarHtml += '<h1>' + year + '年' + '/' + month + '月' + '</h1>'
  calendarHtml += '<table>'
  //曜日の行
  for(let i = 0; i <weeks.length; i++){
    calendarHtml += '<td>' + weeks[i] + '</td>'
  }

  for(let w = 0; w < 6 ; w++){
    calendarHtml += '<tr>'

    for(let d = 0; d < 7; d++){
      if(w == 0 && d < startDay){
        let num = lastMonthendDayCount - startDay + d + 1
        calendarHtml += '<td class = "is-disabled">' + num + '</td>'
      }else if(dayCount > endDayCount){
        let num = dayCount - endDayCount
        calendarHtml += '<td class="is-disabled">' + num + '</td>'
        dayCount++
      }else{
        calendarHtml += `<td class="calendar_td" data-date="${year}/${month}/${dayCount}">${dayCount}</td>`
        dayCount++;
      }
    }
    calendarHtml += '</tr>'
  }
  calendarHtml += '</table>'

  return calendarHtml
}

function moveCalendar(e){
  document.querySelector('#calendar').innerHTML = ''

  if(e.target.id == 'prev'){
    month--

    if(month < 1){
      year--
      month = 12
    }
  }
  if(e.target.id == 'next'){
    month++

    if(month > 12){
      year++
      month = 1
    }
  }

  showCalendar(year,month)

}

document.querySelector('#prev').addEventListener('click', moveCalendar)
document.querySelector('#next').addEventListener('click', moveCalendar)



document.addEventListener("click", function(e){
  e.preventDefault();
  if(e.target.classList.contains('calendar_td')){
    alert(e.target.dataset.date)
  }
})

showCalendar(year,month)

})();


