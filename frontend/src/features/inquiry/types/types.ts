import schema from '../../../../../shared/schemas/inquiry.schema.json';

// schemaの実体からキーを導出
type InquiryFieldKey = keyof typeof schema.properties;

// formDataはキー自動追従
export type InquiryFormData = Record<InquiryFieldKey, string>;

export interface InquiryDom {
  form: HTMLFormElement;
  heading: HTMLHeadingElement;
  stepEdit: HTMLDivElement;
  stepConfirm: HTMLDivElement;
  stepComplete: HTMLDivElement;
  toConfirmButton: HTMLButtonElement;
}

interface InquirySchemaProperty {
  type: 'string';
  title: string;
  description?: string;
  minLength?: number;
  maxLength?: number;
  format?: 'email';
}

export interface InquirySchema {
  $schema: string;
  title: string;
  description: string;
  type: 'object';
  properties: Record<keyof InquiryFormData, InquirySchemaProperty>;
  required: (keyof InquiryFormData)[];
  additionalProperties: boolean;
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
