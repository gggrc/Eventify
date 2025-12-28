<section>
    <header class="section-header">
        <h2>Delete Account</h2>
        <p>Once your account is deleted, all of its resources and data will be permanently lost.</p>
    </header>

    <button type="button" class="btn-primary btn-small" style="background: #dc2626;" onclick="document.getElementById('confirm-modal').style.display='flex'">
        Delete Account
    </button>

    <div id="confirm-modal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; z-index: 1000; padding: 20px;">
        <div class="card" style="margin: 0; padding: 30px; max-width: 400px;">
            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 15px;">Are you absolutely sure?</h2>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="form-group">
                    <label>Enter Password to Confirm</label>
                    <input name="password" type="password" required placeholder="Password">
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button type="button" class="btn-primary btn-small" style="width: auto; padding: 10px 25px; background: #9ca3af;" onclick="document.getElementById('confirm-modal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn-primary btn-small" style="width: auto; padding: 10px 25px; background: #dc2626;"> Confirm Delete</button>
                </div>
            </form>
        </div>
    </div>
</section>