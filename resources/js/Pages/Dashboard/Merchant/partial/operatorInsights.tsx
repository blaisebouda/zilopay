import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";

export default function OperatorInsights() {
  return (
    <Card className="h-full">
      <CardHeader>
        <CardTitle className="text-lg font-semibold">
          Operator Insights
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-8">
        {/* Orange Money */}
        <OperatorInsightCard
          title="Orange Money"
          progressValue={65}
          progressColor="orange"
          totalAmount="2,762,500"
        />

        {/* Moov Money */}
        <OperatorInsightCard
          title="Moov Money"
          progressValue={35}
          progressColor="blue"
          totalAmount="1,487,500"
        />

        <OperatorInsightCard
          title="Autres"
          progressValue={25}
          progressColor="gray"
          totalAmount="487,500"
        />

        {/* Note informative en bas */}
        <div className="mt-10 rounded-lg border border-dashed border-slate-200 bg-slate-50 p-4">
          <p className="text-center text-xs text-accent-foreground italic">
            "Orange Money transactions are up by 8% this week compared to Moov."
          </p>
        </div>
      </CardContent>
    </Card>
  );
}

type OperatorInsightCardProps = {
  title: string;
  progressValue: number;
  progressColor: string;
  totalAmount: string;
};

function OperatorInsightCard({
  title,
  progressValue,
  progressColor,
  totalAmount,
}: OperatorInsightCardProps) {
  return (
    <div className="space-y-2">
      <div className="flex items-center justify-between text-sm font-medium">
        <div className="flex items-center gap-2">
          <span className={`h-2 w-2 rounded-full bg-${progressColor}-500`} />
          <span>{title}</span>
        </div>
        <span className="text-accent-foreground">{progressValue}%</span>
      </div>
      <Progress
        value={progressValue}
        className={`w-full max-w-md *:data-[slot=progress-indicator]:bg-${progressColor}-500 [&>div]:bg-${progressColor}-500/20`}
      />
      <p className="text-xs text-accent-foreground">Total: {totalAmount} XOF</p>
    </div>
  );
}
