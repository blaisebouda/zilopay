import type { LucideIcon } from "lucide-react";
import { Card, CardContent } from "./card";

type EmptyProps = {
  Icon: LucideIcon;
  title: string;
  description: string;
  children?: React.ReactNode;
};

export default function Empty({
  title,
  description,
  Icon,
  children,
}: EmptyProps) {
  return (
    <Card className="text-center py-12">
      <CardContent>
        <Icon className="h-12 w-12 text-gray-400 mx-auto mb-4" />
        <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
          {title}
        </h3>
        <p className="text-gray-600 dark:text-gray-400 mb-4">{description}</p>
        <div className="flex justify-center">{children}</div>
      </CardContent>
    </Card>
  );
}
