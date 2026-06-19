import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import React from "react";

interface FormDialogProps {
  trigger: React.ReactNode;
  title: string;
  description?: string;
  children: React.ReactNode;
  footer?: React.ReactNode;
  onSubmit: () => void;
  loading?: boolean;
  open?: boolean;
  onOpenChange?: (open: boolean) => void;
  showDefaultFooter?: boolean;
  cancelLabel?: string;
  submitLabel?: string;
  contentClassName?: string;
}

export function FormDialog({
  trigger,
  title,
  description,
  children,
  footer,
  loading = false,
  open,
  onSubmit,
  onOpenChange,
  showDefaultFooter = true,
  cancelLabel = "Annuler",
  submitLabel = "Sauvegarder",
  contentClassName = "sm:max-w-[480px]",
}: FormDialogProps) {
  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogTrigger asChild>{trigger}</DialogTrigger>
      <DialogContent className={contentClassName}>
        <DialogHeader>
          <DialogTitle>{title}</DialogTitle>
          {description && <DialogDescription>{description}</DialogDescription>}
        </DialogHeader>

        <div className="py-4">{children}</div>

        {footer ? (
          footer
        ) : showDefaultFooter ? (
          <DialogFooter className="grid grid-cols-2 gap-2">
            <DialogClose asChild>
              <Button className="w-full" variant="outline" type="button">
                {cancelLabel}
              </Button>
            </DialogClose>
            <Button
              className="w-full"
              type="submit"
              disabled={loading}
              onClick={onSubmit}
            >
              {loading ? "Traitement..." : submitLabel}
            </Button>
          </DialogFooter>
        ) : null}
      </DialogContent>
    </Dialog>
  );
}
