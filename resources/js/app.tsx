import "../css/app.css"
//import "./bootstrap"

import { createInertiaApp } from "@inertiajs/react"
import { createRoot } from "react-dom/client"

createInertiaApp({
  id: "app",
  resolve: async (name) => {
    const pages = import.meta.glob("./Pages/**/*.tsx")

    const page: any = await pages[`./Pages/${name}.tsx`]()

    return page
  },

  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />)
  },
})
