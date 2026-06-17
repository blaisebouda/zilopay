function ActionButton({
  onClick,
  icon,
  children,
}: {
  onClick: () => void
  icon: React.ReactNode
  children: React.ReactNode
}) {
  return (
    <button
      onClick={onClick}
      className="flex flex-col items-center gap-2 p-2 hover:bg-primary/10 hover:text-primary hover:rounded-xl"
    >
      <span className="size-12 bg-primary/10 rounded-full flex text-primary items-center justify-center">
        {icon}
      </span>
      <span>{children}</span>
    </button>
  )
}

export { ActionButton }
