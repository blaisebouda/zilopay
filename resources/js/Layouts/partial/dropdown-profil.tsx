import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogMedia,
    AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import ENDPOINTS from "@/constants/endpoints";
import { usePost } from "@/hooks/use-post";
import { formatInitials, wait } from "@/lib/utils";
import LS from "@/lib/ls";
import { Loader2, LogOutIcon, SettingsIcon } from "lucide-react";
import type { ReactNode } from "react";
import { useState } from "react";
import { toast } from "sonner";

type Props = {
    trigger: ReactNode;
    defaultOpen?: boolean;
    align?: "start" | "center" | "end";
};

const ProfileDropdown = ({ trigger, defaultOpen, align = "end" }: Props) => {
    const user = LS.get("user");
    const [logoutDialogOpen, setLogoutDialogOpen] = useState(false);
    return (
        <div>
            <LogoutDialog
                open={logoutDialogOpen}
                onOpenChange={setLogoutDialogOpen}
            />

            <DropdownMenu defaultOpen={defaultOpen}>
                <DropdownMenuTrigger asChild>{trigger}</DropdownMenuTrigger>
                <DropdownMenuContent className="w-80" align={align || "end"}>
                    <DropdownMenuLabel className="flex items-center gap-4 px-4 py-2.5 font-normal">
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
                    </DropdownMenuLabel>

                    <DropdownMenuSeparator />

                    <DropdownMenuGroup>
                        <DropdownMenuItem className="px-4 py-2.5 text-base">
                            <SettingsIcon className="text-foreground size-5" />
                            <span>Paramètres</span>
                        </DropdownMenuItem>
                    </DropdownMenuGroup>

                    <DropdownMenuItem
                        variant="destructive"
                        className="px-4 py-2.5 text-base"
                        onClick={() => setLogoutDialogOpen(true)}
                    >
                        <LogOutIcon className="size-5" />
                        <span>Déconnexion</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    );
};

type LogoutDialogProps = {
    open: boolean;
    onOpenChange: (open: boolean) => void;
};

function LogoutDialog({ open, onOpenChange }: LogoutDialogProps) {
    const { post, loading } = usePost(ENDPOINTS.AUTH.logout);

    const logout = async () => {
        onOpenChange(true);
        await post({});
        toast.success("Déconnexion réussie");
        await wait(1200);
        window.location.href = "/login";
    };

    return (
        <AlertDialog open={open} onOpenChange={onOpenChange}>
            <AlertDialogContent size="sm">
                <AlertDialogHeader>
                    <AlertDialogMedia className="bg-destructive/10 text-destructive dark:bg-destructive/20 dark:text-destructive">
                        <LogOutIcon />
                    </AlertDialogMedia>
                    <AlertDialogTitle>Déconnexion</AlertDialogTitle>
                    <AlertDialogDescription>
                        Êtes-vous sûr de vouloir vous déconnecter ?
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel variant="outline">
                        Annuler
                    </AlertDialogCancel>
                    <Button
                        size="default"
                        variant="destructive"
                        onClick={logout}
                        disabled={loading}
                    >
                        {loading && <Loader2 className="size-4 animate-spin" />}
                        Se déconnecter
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    );
}

export default ProfileDropdown;
