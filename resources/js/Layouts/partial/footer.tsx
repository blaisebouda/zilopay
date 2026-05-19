export default function Footer() {
    return (
        <footer className="py-16">
            <div className="text-muted-foreground mx-auto flex size-full max-w-7xl items-center justify-center gap-3 px-4 py-3 max-sm:flex-col sm:gap-6 sm:px-6">
                <p className="text-sm text-balance max-sm:text-center">
                    {`©${new Date().getFullYear()}`}{" "}
                    <a href="#" className="text-primary">
                        Zilopay
                    </a>
                    , Tous droits réservés
                </p>
                {/* <div className="flex items-center gap-5">
                    <a href="#">
                        <Facebook className="size-4" />
                    </a>
                    <a href="#">
                        <Instagram className="size-4" />
                    </a>
                    <a href="#">
                        <LinkedinIcon className="size-4" />
                    </a>
                    <a href="#">
                        <TwitterIcon className="size-4" />
                    </a>
                </div> */}
            </div>
        </footer>
    );
}
