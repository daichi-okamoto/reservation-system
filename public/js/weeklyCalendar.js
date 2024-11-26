  export function renderWeeklyCalendar(calendarElement, currentDate) {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const dayOfMonth = currentDate.getDate();
    const dayOfWeek = currentDate.getDay();

    // 週の開始日を計算
    const startOfWeek = new Date(year, month, dayOfMonth - dayOfWeek);

    // 見出しの追加
    const header = document.createElement('h3');
    header.textContent = `${year}年 ${month + 1}月 第${Math.ceil(dayOfMonth / 7)}週`;
    calendarElement.appendChild(header);

    // 曜日の見出しを作成
    const daysOfWeek = ['日', '月', '火', '水', '木', '金', '土'];
    const daysRow = document.createElement('div');
    daysRow.className = 'calendar-weekly';

    // 時間のヘッダー要素（左上に「時間」というラベルを追加）
    const timeHeader = document.createElement('div');
    timeHeader.className = 'calendar-day-header';
    timeHeader.textContent = '時間/日付';
    daysRow.appendChild(timeHeader);

    // 曜日ヘッダーを作成
    daysOfWeek.forEach((day, index) => {
      const date = new Date(startOfWeek); // 週の開始日から計算
      date.setDate(startOfWeek.getDate() + index);

      // 曜日ヘッダー要素
      const dayElement = document.createElement('div');
      dayElement.className = 'calendar-day-header';

      // 曜日と日付をヘッダーに設定
      dayElement.textContent = `${date.getMonth() + 1}/${date.getDate()} (${day})`;

      daysRow.appendChild(dayElement);
    });

    // 曜日ヘッダーをカレンダーに追加
    calendarElement.appendChild(daysRow);

    // 時間行（8:00〜21:00）を作成
    const hours = Array.from({ length: 14 }, (_, i) => `${8 + i}:00`);
    const calendarGrid = document.createElement('div');
    calendarGrid.className = 'calendar-grid-weekly';

    // 時間ごとの行を生成
    hours.forEach(hour => {
      const timeRow = document.createElement('div');
      timeRow.className = 'time-row';

      // 時間ラベル（最初の左側の時間カラム）
      const timeLabel = document.createElement('div');
      timeLabel.className = 'time-label';
      timeLabel.textContent = hour;
      timeRow.appendChild(timeLabel);

      // 各曜日のセルを生成
      for (let i = 0; i < 7; i++) {
        const date = new Date(startOfWeek);
        date.setDate(startOfWeek.getDate() + i);

        const cell = document.createElement('div');
        cell.className = 'time-cell';

        // セルに日付と時間をデータ属性として追加（後で予約情報を操作するため）
        cell.dataset.date = `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
        cell.dataset.time = hour;

        // セルに基本テキストを設定
        cell.textContent = '⚪︎';
        timeRow.appendChild(cell);
      }

      // 時間行をカレンダーグリッドに追加
      calendarGrid.appendChild(timeRow);
    });

    // カレンダーグリッドをカレンダーに追加
    calendarElement.appendChild(calendarGrid);
  }
