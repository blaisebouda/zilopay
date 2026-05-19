import {
    ArrowRightLeftIcon,
    BanknoteArrowDown,
    Building2,
    ChartSplineIcon,
    KeyIcon,
    SettingsIcon,
    Wallet2,
} from "lucide-react";

import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarProvider,
    SidebarTrigger,
} from "@/components/ui/sidebar";

import { RouterLink as NavLink } from "@/components/ui/nav-link";
import ThemeToggle from "@/components/ui/theme-toggle";

import LS from "@/lib/ls";
import { ROUTE } from "@/router/route";
import { MainLayout } from "./MainLayout";
import ProfileDropdown from "./partial/dropdown-profil";

const isMerchant = LS.get("user")?.is_merchant || false;

const NAV_ITEMS = [
    {
        name: "Accueil",
        items: [
            {
                title: "Tableau de bord",
                url: ROUTE.DASHBOARD,
                icon: ChartSplineIcon,
            },
            {
                title: "Coffres",
                url: ROUTE.VAULT,
                icon: Wallet2,
            },
            {
                title: "Transactions",
                url: ROUTE.TRANSACTIONS,
                icon: ArrowRightLeftIcon,
            },
            {
                title: "Retraits",
                url: ROUTE.WITHDRAWS,
                icon: BanknoteArrowDown,
            },
        ],
    },
    {
        name: "Marchants",
        items: [
            {
                title: "Compte marchand",
                url: ROUTE.MERCHANTS,
                icon: Building2,
            },
            ...(isMerchant
                ? [
                      {
                          title: "Clés API",
                          url: ROUTE.API_KEYS,
                          icon: KeyIcon,
                      },
                  ]
                : []),
        ],
    },

    {
        name: "Configuration",
        items: [
            {
                title: "Paramètres",
                url: ROUTE.SETTINGS,
                icon: SettingsIcon,
            },
        ],
    },
];

export default function DashboardLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    return (
        <div className="flex min-h-dvh w-full">
            <SidebarProvider>
                <Sidebar>
                    <SidebarContent>
                        <SidebarHeader>
                            <SidebarMenu>
                                <SidebarMenuItem>
                                    <SidebarMenuButton size="lg" asChild>
                                        <a href="#">
                                            <img
                                                src="/logo.jpeg"
                                                alt="Zilopay"
                                                className="size-10 border rounded-lg"
                                            />
                                            <div className="grid flex-1 text-left text-sm leading-tight">
                                                <span className="truncate font-medium">
                                                    Zilopay
                                                </span>
                                                <span className="truncate text-xs">
                                                    Digital Wallet
                                                </span>
                                            </div>
                                        </a>
                                    </SidebarMenuButton>
                                </SidebarMenuItem>
                            </SidebarMenu>
                        </SidebarHeader>
                        {NAV_ITEMS.map((nav) => (
                            <SidebarGroup key={nav.name}>
                                <SidebarGroupLabel>
                                    {nav.name}
                                </SidebarGroupLabel>
                                <SidebarGroupContent>
                                    <SidebarMenu>
                                        {nav.items.map((item) => (
                                            <SidebarMenuItem key={item.title}>
                                                <SidebarMenuButton asChild>
                                                    <NavLink
                                                        key={item.url}
                                                        href={item.url}
                                                        title={item.title}
                                                        icon={
                                                            <item.icon
                                                                size={16}
                                                            />
                                                        }
                                                    />
                                                </SidebarMenuButton>
                                                {/* {item.badge && (
                          <SidebarMenuBadge className="top-1.5 right-1 bg-primary/10 rounded-full">
                            {item.badge}
                          </SidebarMenuBadge>
                        )} */}
                                            </SidebarMenuItem>
                                        ))}
                                    </SidebarMenu>
                                </SidebarGroupContent>
                            </SidebarGroup>
                        ))}
                    </SidebarContent>
                </Sidebar>

                <div className="flex flex-1 flex-col">
                    <header className="bg-card sticky top-0 z-50 border-b">
                        <div className="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-2 sm:px-6">
                            <div className="flex items-center gap-2">
                                <SidebarTrigger className="[&_svg]:size-5" />
                                <Separator
                                    orientation="vertical"
                                    className="hidden h-4 sm:block"
                                />

                                {/* breadcrumbs */}
                                <div className="hidden sm:block">
                                    <h2 className="font-bold text-muted-foreground">
                                        Tableau de bord
                                    </h2>
                                </div>
                            </div>
                            <div className="flex items-center gap-1.5">
                                <ThemeToggle />

                                <ProfileDropdown
                                    trigger={
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            className="size-9.5"
                                        >
                                            <Avatar className="size-9.5 rounded-md">
                                                <AvatarImage src="https://cdn.shadcnstudio.com/ss-assets/avatar/avatar-1.png" />
                                                <AvatarFallback>
                                                    JD
                                                </AvatarFallback>
                                            </Avatar>
                                        </Button>
                                    }
                                />
                            </div>
                        </div>
                    </header>

                    <MainLayout>{children}</MainLayout>
                </div>
            </SidebarProvider>
        </div>
    );
}
