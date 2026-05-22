import { CopyButton } from "./copy-button";

type CodePros = {
  text: string;
  canCopy?: boolean;
};
export function Code({ text, canCopy = true }: CodePros) {
  return (
    <div className="w-full flex items-center gap-2">
      <code className="border overflow-x-hidden max-w-xl text-sm bg-muted px-2 py-1 rounded-sm">
        {text}
      </code>
      {canCopy && <CopyButton value={text} />}
    </div>
  );
}
