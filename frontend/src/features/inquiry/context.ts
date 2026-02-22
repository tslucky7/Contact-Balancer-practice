export const dom = {
  form: document.getElementById('inquiryForm') as HTMLFormElement,
  heading: document.getElementById('inquiry-heading') as HTMLHeadingElement,
  stepEdit: document.getElementById('step-edit') as HTMLDivElement,
  stepConfirm: document.getElementById('step-confirm') as HTMLDivElement,
  stepComplete: document.getElementById('step-complete') as HTMLDivElement,
  toConfirmButton: document.getElementById('inquiry-to-confirm-button') as HTMLButtonElement,
};

export const state = {
  name: '',
  email: '',
  subject: '',
  message: '',
};

export const STEPS = {
  EDIT: '/',
  CONFIRM: '/confirm',
  COMPLETE: '/complete',
} as const;

export type Step = typeof STEPS[keyof typeof STEPS];
