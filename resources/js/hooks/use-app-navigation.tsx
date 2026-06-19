import { router } from "@inertiajs/react"

/**
 * Hook pour gérer la navigation dans l'app
 */
export function useAppNavigation() {
  /**
   * Navigation simple
   */
  const goTo = (path: string) => {
    router.visit(path.startsWith("/") ? path : `/${path}`)
  }

  const goToDashboard = (path: string) => {
    goTo(`dashboard/${path}`)
  }

  /**
   * Navigation avec replace (pas d'historique)
   */
  const replace = (path: string) => {
    router.visit(path.startsWith("/") ? path : `/${path}`, {
      replace: true,
    })
  }

  return {
    goTo,
    replace,
    goToDashboard,
  }
}
