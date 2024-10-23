<h1>User Profile</h1>

<p>Name: {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p>

<h2>Borrowed Books</h2>

<ul>
    @foreach($borrowedBooks as $book)
        <li>{{ $book->title }} ({{ $book->author }})</li>
    @endforeach
</ul>

<!-- Button to borrow a book -->
<button class="btn btn-success">Pinjam</button>

<!-- Form to borrow a book (hidden by default) -->
<form id="borrow-book-form" style="display: none;">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <input type="hidden" name="book_id" value="{{ /* book ID will be populated via JavaScript */ }}">
    <button type="submit" class="btn btn-success">Pinjam</button>
</form>
