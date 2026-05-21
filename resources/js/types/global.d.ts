import "@inertiajs/core"

declare module "@inertiajs/core" {
  export interface InertiaConfig {
    sharedPageProps: {
      auth: { user: { name: string } | null }
    }
    flashDataType: {
      toast?: { type: "success" | "error"; message: string }
    }
    errorValueType: string[]
    layoutProps: {
      title: string
      showSidebar: boolean
    }
    namedLayoutProps: {
      app: { title: string; theme: "light" | "dark" }
      content: { padding: string; maxWidth: string }
    }
  }
}
