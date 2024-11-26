// 月間カレンダーをレンダリングする関数
export function renderMonthlyCalendar(calendarElement, currentDate) {
  // 現在の年と月を取得
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();

  // 月の最初の日の曜日を取得
  const firstDayOfMonth = new Date(year, month, 1).getDay();

  // 月の最終日の日付を取得
  const lastDateOfMonth = new Date(year, month + 1, 0).getDate();

  // カレンダーの見出しを追加
  const header = document.createElement('h3');
  header.textContent = `${year}年 ${month + 1}月`; // 月は0から始まるため+1
  calendarElement.appendChild(header);

  // 曜日（日〜土）の行を作成してカレンダーに追加
  const daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
  const daysRow = document.createElement('div');
  daysRow.className = 'calendar-row'; 

  // 曜日をループで生成し、行に追加
  daysOfWeek.forEach(day => {
    const dayElement = document.createElement('div');
    dayElement.className = 'calendar-day-header'; // CSSクラス
    dayElement.textContent = day; // 曜日のテキストを設定
    daysRow.appendChild(dayElement);
  });

  // 曜日ヘッダーをカレンダーに追加
  calendarElement.appendChild(daysRow);

  // 日付セルを格納するグリッド要素を作成
  const calendarGrid = document.createElement('div');
  calendarGrid.className = 'calendar-grid-monthly'; // CSSでグリッドのスタイルを適用

  // 月の最初の日までの空白セルを作成
  for (let i = 0; i < firstDayOfMonth; i++) {
    const emptyCell = document.createElement('div');
    emptyCell.className = 'calendar-cell empty'; // 空白セルのCSSクラス
    calendarGrid.appendChild(emptyCell);
  }

  // 月の日付セルを作成し、グリッドに追加
  for (let day = 1; day <= lastDateOfMonth; day++) {
    const dateCell = document.createElement('div');
    dateCell.className = 'calendar-cell'; // 通常の日付セルのCSSクラス

    // 今日の日付と一致する場合に特別なスタイルを適用
    const today = new Date(); // 今日の日付を取得
    if (
      day === today.getDate() &&
      month === today.getMonth() &&
      year === today.getFullYear()
    ) {
      dateCell.classList.add('today'); // CSSで今日の日付を強調表示するクラス
    }

    // 日付をセルに設定
    dateCell.textContent = day;

    // グリッドに日付セルを追加
    calendarGrid.appendChild(dateCell);
  }

  // 完成したグリッドをカレンダーに追加
  calendarElement.appendChild(calendarGrid);
}
