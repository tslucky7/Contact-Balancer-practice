import { dom } from "../state/context";

/**
 * ヘッダーのタイトルを設定
 */
export const setHeading = (text: string): void => {
  dom.heading.textContent = text;
}

/**
 * ヘッダーのタイトルを設定
 */
export const createHeading = (text: string): HTMLHeadingElement => {
  const headingElement = document.createElement('h1');
  headingElement.id = 'inquiry-heading';
  headingElement.className = 'heading font-bold text-3xl';
  headingElement.textContent = text;

  return headingElement;
}
