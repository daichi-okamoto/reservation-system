import { renderWeeklyCalendar } from './weeklyCalendar.js';
import { renderMonthlyCalendar } from './monthlyCalendar.js';

document.addEventListener('DOMContentLoaded', function () {
  const calendarElement = document.getElementById('calendar');
  const weeklyViewButton = document.getElementById('weekly-view');
  const monthlyViewButton = document.getElementById('monthly-view');
  const prevButton = document.getElementById('prev-month');
  const nextButton = document.getElementById('next-month');
  const todayButton = document.getElementById('day');

  let currentDate = new Date();  // 現在の表示中の日時
  let todayDate = new Date();  // 今日の日付を保存
  let isWeeklyView = false; // 初期は月間表示

  // カレンダーをレンダリングする関数
  function renderCalendar() {
    calendarElement.innerHTML = '';
    if (isWeeklyView) {
      renderWeeklyCalendar(calendarElement, currentDate);
    } else {
      renderMonthlyCalendar(calendarElement, currentDate);
    }
    updateButtonLabels();
  }

  // ボタンのラベルを更新する関数
  function updateButtonLabels() {
    if (isWeeklyView) {
      prevButton.textContent = '前週';
      nextButton.textContent = '翌週';
    } else {
      prevButton.textContent = '前月';
      nextButton.textContent = '翌月';
    }
  }

  // ボタンのイベントリスナー
  weeklyViewButton.addEventListener('click', () => {
    isWeeklyView = true; // 週間表示に切り替え
    renderCalendar(); // カレンダーを再描画
  });

  monthlyViewButton.addEventListener('click', () => {
    isWeeklyView = false; // 月間表示に切り替え
    renderCalendar(); // カレンダーを再描画
  });

  prevButton.addEventListener('click', () => {
    if (isWeeklyView) {
      currentDate.setDate(currentDate.getDate() - 7); // 週間表示: 7日戻る
    } else {
      currentDate.setMonth(currentDate.getMonth() - 1); // 月間表示: 1か月戻る
    }
    renderCalendar();
  });

  nextButton.addEventListener('click', () => {
    if (isWeeklyView) {
      currentDate.setDate(currentDate.getDate() + 7); // 週間表示: 7日進む
    } else {
      currentDate.setMonth(currentDate.getMonth() + 1); // 月間表示: 1か月進む
    }
    renderCalendar();
  });

  todayButton.addEventListener('click', () => {
    currentDate = new Date(todayDate); // 現在の日付に戻す
    renderCalendar(); // 表示をリセット
  });

  // 最初に月間カレンダーを表示
  renderCalendar();
});
