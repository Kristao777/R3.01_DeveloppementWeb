<h1>Formulaire de contact</h1>

<form action="?c=Contact&a=enregistrer" method="post">
    <div class="mb-3">
        <label for="nom" class="form-label">Votre nom</label>
        <input type="text" class="form-control" name="nom" id="nom" required>
    </div>
    <div class="mb-3">
        <label for="mail" class="form-label">Votre mail</label>
        <input type="email" class="form-control" name="mail" id="mail" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary" id="enregistrer">Enregistrer</button>
    </div>
</form>