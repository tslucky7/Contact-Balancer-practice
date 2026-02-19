import { createCompleteContent } from "../components/completeContent";

export const toCompleteHandler = (): void => {
  createCompleteContent();
  console.log('送信完了');
}