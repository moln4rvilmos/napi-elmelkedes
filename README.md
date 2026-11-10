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
| `.github/workflows/deploy.yml` | Automatikus kiküldés a szerverre |

## Telepítés / Fejlesztés

A projekt statikus HTML/JS/CSS, nincs build lépés. Elég egy webszerver a gyökérből kiszolgálni.

## Deploy

A `main` ágra történő minden feltöltés (push) után a GitHub Actions automatikusan kimásolja a weboldal
fájljait az aa.vilix.hu-ra (Nethely, `home/AA`), SSH-kulccsal (`NETHELY` repository secret).
Csak a weboldalhoz kellő fájlok mennek ki; az Android-app és a fejlesztői fájlok a GitHubon maradnak.
