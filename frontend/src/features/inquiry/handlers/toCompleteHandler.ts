import { createCompleteContent } from "../components/completeContent";
import { dom } from "../state/context";
import { STEPS } from "../types/types";
import { setHeading } from "../components/heading";

/**
 * 完了画面を動的に表示する
 * @returns
 */
export const toCompleteHandler = (): void => {
  if (!dom.stepEdit.classList.contains('hidden')) {
    dom.stepEdit.classList.add('hidden');
  }

  dom.stepConfirm.replaceChildren();

  setHeading('送信完了');
  const completeContent = createCompleteContent();
  dom.stepComplete.replaceChildren();
  dom.stepComplete.append(completeContent);

  console.log('送信完了'); 
  history.pushState({ step: STEPS.COMPLETE }, '', STEPS.COMPLETE);
}
