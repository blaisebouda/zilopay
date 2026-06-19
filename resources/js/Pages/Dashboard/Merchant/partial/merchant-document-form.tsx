"use client"

import { Button } from "@/components/ui/button"
import {
  FileUpload,
  FileUploadDropzone,
  FileUploadItem,
  FileUploadItemDelete,
  FileUploadItemMetadata,
  FileUploadItemPreview,
  FileUploadList,
  FileUploadTrigger,
} from "@/components/ui/file-upload"
import type { MerchantFormData } from "@/lib/validations/merchant.schema"
import { Upload, X } from "lucide-react"
import * as React from "react"
import { useEffect } from "react"
import type { UseFormReturn } from "react-hook-form"
import { toast } from "sonner"

interface MerchantDocumentFormProps {
  form: UseFormReturn<MerchantFormData>
}

export default function MerchantDocumentForm({
  form,
}: MerchantDocumentFormProps) {
  const [files, setFiles] = React.useState<File[]>([])

  // Sync files with react-hook-form
  useEffect(() => {
    form.setValue("documents", files, { shouldValidate: true })
  }, [files, form])

  const onFileReject = React.useCallback((file: File, message: string) => {
    toast(message, {
      description: `"${file.name.length > 20 ? `${file.name.slice(0, 20)}...` : file.name}" a été rejeté`,
    })
  }, [])

  return (
    <FileUpload
      maxFiles={2}
      maxSize={5 * 1024 * 1024}
      className="w-full"
      value={files}
      onValueChange={setFiles}
      onFileReject={onFileReject}
      multiple
      accept=".pdf"
    >
      <FileUploadDropzone>
        <div className="flex flex-col items-center gap-1 text-center">
          <div className="flex items-center justify-center rounded-full border p-2.5">
            <Upload className="size-6 text-muted-foreground" />
          </div>
          <p className="font-medium text-sm">Téléchargez vos documents</p>
          <p className="text-muted-foreground text-xs">
            Cliquez pour parcourir (max 2 fichiers, jusqu'à 5MB chacun)
          </p>
        </div>
        <FileUploadTrigger>
          <Button type="button" size="sm" variant="outline">
            Parcourir les fichiers
          </Button>
        </FileUploadTrigger>
      </FileUploadDropzone>
      <FileUploadList>
        {files.map((file, index) => (
          <FileUploadItem key={index} value={file}>
            <FileUploadItemPreview />
            <FileUploadItemMetadata />
            <FileUploadItemDelete>
              <Button variant="ghost" size="icon" className="size-7">
                <X />
              </Button>
            </FileUploadItemDelete>
          </FileUploadItem>
        ))}
      </FileUploadList>
    </FileUpload>
  )
}
