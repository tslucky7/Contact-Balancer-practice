import schema from '../../../../../shared/schemas/inquiry.schema.json';
import type { InquirySchema } from '../types/types';
import { Button } from '../../../components/ui/Button';
import type { InquiryFormData } from '../types/types';
import { ConfirmField } from './ConfirmField';
import { Heading } from '../../../components/ui/heading';

interface ConfirmViewProps {
  data: InquiryFormData;
  onBack: () => void;
  onComplete: () => void;
}

/**
 * 引数で渡された key が、Schema の properties に定義されているプロパティ名かどうかをチェックし、定義されている場合はtitle、定義されていない場合はkey をそのまま返す
 * - 引数で渡された key が、Schema の properties に定義されているプロパティ名かどうかをチェック
 * - 定義されている場合はtitle、定義されていない場合はkey をそのまま返す
 * @param key - プロパティ名
 * @returns
 */
const getLabelFromSchema = (key: keyof InquirySchema['properties']): string => {
  return schema.properties[key]?.title || key;
};

export const ConfirmView = ({ data, onBack, onComplete }: ConfirmViewProps) => {
  return (
    <div id="step-confirm" className="">
      <Heading className="text-center">お問い合わせ内容の確認</Heading>
      <div className="flex flex-col gap-y-4">
        {(Object.entries(data) as [keyof InquiryFormData, string][]).map(
          ([key, value]) => (
            <ConfirmField
              key={key}
              label={getLabelFromSchema(key)}
              value={value}
            />
          ),
        )}
      </div>
      <div className="flex justify-end gap-x-4">
        <Button buttonType="button" text="戻る" eventHandler={onBack} />
        <Button buttonType="submit" text="送信する" eventHandler={onComplete} />
      </div>
    </div>
  );
};
