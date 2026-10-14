def krok_symulacji(stan, sprzężenie_lokalne, ε, Ṡ_export, radiator_CMB, gęstość_ewaluacji):

    # 1. Sparse Law – oszczędzanie zasobów / dekoherencja
    if sprzężenie_lokalne == "SILNE":
        stan = ewaluuj_stan_klasyczny(stan)      # dekoherencja, fizyczny pomiar
    else:
        stan = utrzymuj_superpozycję(stan)       # brak pełnych obliczeń (Sparse Law)

    # 2. Dyskryminator / Walidator symplektyczny (Noether + unitarność)
    if łamie_prawa_zachowania(stan, ε):          # energia, pęd, ładunek, unitarność
        return odrzuć_trajektorię(stan)          # natychmiastowe odcięcie
    else:
        stan = zaakceptuj_krok(stan)

    # 3. Cumulative Variance Drift (gdy ε > 0)
    if ε > 0:
        wariancja += ε * dt
        if wariancja > próg_koherencji:
            return utrata_koherencji(stan)       # system traci klasyczny obraz

    # 4. Radiator Law / granica Landauera
    if Ṡ_export > przepustowość_radiatora_CMB:
        return przegrzanie_i_kolaps()            # awaria układu
    elif Ṡ_export == 0:
        return termodynamiczna_śmierć(stan)      # brak wolnej energii

    # 5. Bekenstein + Margolus-Levitin (gdy gęsta ewaluacja)
    if gęstość_ewaluacji == "PEŁNA" and Sparse == "OFF":
        if przekroczono_granice_Bekenstein_Margolus():
            return zapadanie_grawitacyjne()

    # 6. Observer – kompresja do klasycznego obrazu
    if not Observer.może_skompresować(stan):
        return nieczytelny_stan()                # awatar nie widzi już spójnego świata

    return stan
