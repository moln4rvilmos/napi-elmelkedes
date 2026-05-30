# Napi Elmélkedés

Magyar nyelvű AA (Névtelen Alkoholisták) napi elmélkedések webapplikáció.

## Funkciók

- **Napi tartalom** – az aktuális nap elmélkedése automatikusan megjelenik megnyitáskor
- **Dátum és névnap** – a mai teljes dátum és a névnaposok neve
- **Naptár** – tetszőleges nap elmélkedése megtekinthető a naptár ikonnal
- **Józanság számláló** – az első tiszta nap megadásával megmutatja, hány napja vagy józan; évfordulókon különleges üzenettel
- **Sötét/világos mód** – automatikusan a rendszer témájához igazodik
- **Reszponzív** – asztali gépen kártyás, mobilon teljes szélességű megjelenés
- **PWA** – telepíthető alkalmazásként, offline is működik (Service Worker)

## Fájlok

| Fájl | Leírás |
|------|--------|
| `index.html` | Fő oldal, JavaScript logika |
| `napok.js` | 366 nap tartalma (MM-DD kulcsokkal) |
| `nevnapok.js` | Magyar névnap naptár, szökőéves variánsokkal |
| `stilus.css` | Stíluslap (világos + sötét mód) |
| `service-worker.js` | PWA offline cache |
| `manifest.json` | PWA manifest |
| `deploy.sh` | FTP deploy szkript |

## Telepítés / Fejlesztés

A projekt statikus HTML/JS/CSS, nincs build lépés. Elég egy webszerver a gyökérből kiszolgálni.

## Deploy

**GitHub:**
```bash
git add -u
git commit -m "Leírás"
git push origin main
```

**Webszerver (Nethely FTP):**
```bash
./deploy.sh eles
```

Teszt feltöltés (`/AA/teszt` mappába):
```bash
./deploy.sh
```

> A GitHub token a remote URL-ben van tárolva. Ha tokent újítasz meg:
> ```bash
> git remote set-url origin https://UJTOKEN@github.com/moln4rvilmos/napi-elmelkedes.git
> ```
