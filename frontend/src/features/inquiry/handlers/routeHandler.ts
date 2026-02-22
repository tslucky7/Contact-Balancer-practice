import { dom, state } from "../state/context";
import { STEPS } from "../types/types";

/**
 * ルートを処理する
 * @returns
 */
export const routeHandler = (): void => {
  const pathname = window.location.pathname;

  switch (pathname) {
    case STEPS.EDIT:
      // URLが入力画面なら、入力画面を表示する処理を呼ぶ
      console.log('case: STEPS.EDIT');
      dom.stepEdit.classList.remove('hidden');
      dom.stepConfirm.replaceChildren();
      dom.stepComplete.replaceChildren();

      break;
    case STEPS.CONFIRM:
      // 入力データがない場合は入力画面へ戻すなどの制御もここで行う
      console.log('case: STEPS.CONFIRM');
      if (!state.name || !state.email || !state.subject || !state.message) {
        history.replaceState(null, '', STEPS.EDIT);
        return;
      }

      // URLが確認画面なら、確認画面を表示する処理を呼ぶ
      dom.stepEdit.classList.add('hidden');
      dom.stepComplete.replaceChildren();
      
      break;
    case STEPS.COMPLETE:
      // URLが完了画面なら、完了画面を表示する
      console.log('case: STEPS.COMPLETE');
      dom.stepEdit.classList.add('hidden');
      dom.stepConfirm.replaceChildren();

      break;
    default:
      // それ以外（空や #edit）なら、入力画面を表示する
      console.log('case: default');
      dom.stepEdit.classList.remove('hidden');
      dom.stepConfirm.replaceChildren();
      dom.stepComplete.replaceChildren();

      break;
  }
};
