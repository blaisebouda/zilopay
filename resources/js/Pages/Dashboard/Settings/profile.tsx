import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import Heading from "@/components/ui/heading"
import { useGet } from "@/hooks/use-get"
import { formatInitials } from "@/lib/utils"
import { User } from "@/types/interface"
import FormProfile from "./partial/form-profile"

export default function Profile() {
  const { result: user } = useGet<User>("auth/me")

  return (
    <div className="space-y-6">
      <Heading
        title="Profil"
        description="Gérez vos informations personnelles"
      />
      <div className="flex items-center gap-4">
        <div className="relative">
          <Avatar className="size-10">
            <AvatarImage
              src="https://cdn.shadcnstudio.com/ss-assets/avatar/avatar-1.png"
              alt="John Doe"
            />
            <AvatarFallback>
              {formatInitials(user?.name || "Z P")}
            </AvatarFallback>
          </Avatar>
          <span className="ring-card absolute right-0 bottom-0 block size-2 rounded-full bg-green-600 ring-2" />
        </div>
        <div className="flex flex-1 flex-col items-start">
          <span className="text-foreground text-lg font-semibold">
            {user?.name}
          </span>
          <span className="text-muted-foreground text-base">
            {user?.email || user?.phone_number}
          </span>
        </div>
      </div>
      {user && <FormProfile user={user} />}
    </div>
  )
}
