import type { InquiryFormData } from "../types/types"

/**
 * フォームのデータをstateに更新する
 * @param form
 * @param state
 * @returns
 */
export const readForm = (form: HTMLFormElement, state: InquiryFormData): InquiryFormData => {
  const formData = new FormData(form);

  state.name = formData.get('name') as string;
  state.email = formData.get('email') as string;
  state.subject = formData.get('subject') as string;
  state.message = formData.get('message') as string;

  return state;
}

/**
 * stateのデータをフォームに更新する
 * @param form
 * @param state
 */
export const writeForm = (form: HTMLFormElement, state: InquiryFormData): void => {
  (form.elements.namedItem('name') as HTMLInputElement).value = state.name || '';
  (form.elements.namedItem('email') as HTMLInputElement).value = state.email || '';
  (form.elements.namedItem('subject') as HTMLSelectElement).value = state.subject || '';
  (form.elements.namedItem('message') as HTMLTextAreaElement).value = state.message || '';
}

/**
 * sessionStorage から保存された問い合わせデータを取得する
 * @returns
 */
export const loadSession = (): InquiryFormData | null => {
  const saved = sessionStorage.getItem('inquiry');
  if (!saved) return null;
  try {
    return JSON.parse(saved);
  } catch (e) {
    console.error('Failed to parse session data', e);
    return null;
  }
}

/**
 * sessionStorage に問い合わせデータを保存する
 * @param state
 */
export const saveSession = (state: InquiryFormData): void => {
  sessionStorage.setItem('inquiry', JSON.stringify(state));
}
