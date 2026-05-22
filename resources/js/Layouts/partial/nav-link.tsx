import { cn } from "@/lib/utils"
import { Link, usePage } from "@inertiajs/react"

function NavLink({
  href,
  title,
  icon,
  ...props
}: {
  href: string
  title: string
  icon?: React.ReactNode
}) {
  const { url } = usePage()

  const active = url === href

  return (
    <Link
      href={href}
      {...props}
      className={cn(
        "flex items-center gap-2 px-2 py-1.5 text-sm font-medium rounded-lg",
        active
          ? "bg-accent text-accent-foreground dark:bg-accent/50"
          : "hover:bg-accent hover:text-accent-foreground dark:hover:bg-accent/50"
      )}
    >
      {icon && <span>{icon}</span>}
      <span>{title}</span>
    </Link>
  )
}

export { NavLink }
