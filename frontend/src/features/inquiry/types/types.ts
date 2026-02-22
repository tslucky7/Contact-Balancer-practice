export interface InquiryState {
  name: string;
  email: string;
  subject: string;
  message: string;
}

export interface InquiryDom {
  form: HTMLFormElement;
  heading: HTMLHeadingElement;
  stepEdit: HTMLDivElement;
  stepConfirm: HTMLDivElement;
  stepComplete: HTMLDivElement;
  toConfirmButton: HTMLButtonElement;
}

export const STEPS: {
  EDIT: string;
  CONFIRM: string;
  COMPLETE: string;
} = {
  EDIT: '/',
  CONFIRM: '/confirm',
  COMPLETE: '/complete',
} as const;

export type Step = typeof STEPS[keyof typeof STEPS];
