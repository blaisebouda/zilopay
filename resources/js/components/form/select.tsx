import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "../ui/select";

interface SelectSimpleProps {
  options: { value: string; label: string }[];
  placeholder?: string;
  onValueChange?: (value: string) => void;
  value?: string;
  searchPlaceholder?: string;
  emptyMessage?: string;
  label?: string;
  description?: string;
}

export function SimpleSelect({
  options,
  placeholder,
  onValueChange,
  value,
}: SelectSimpleProps) {
  return (
    <Select onValueChange={onValueChange} value={value}>
      <SelectTrigger className="w-full">
        <SelectValue placeholder={placeholder || "Sélectionnez une option"} />
      </SelectTrigger>

      <SelectContent>
        {options.map((option) => (
          <SelectItem key={option.value} value={option.value}>
            {option.label}
          </SelectItem>
        ))}
      </SelectContent>
    </Select>
  );
}
