<div id="enterCodeModal" tabindex="-1" aria-hidden="true" role="dialog"
     class="modal fade hidden bg-secondary bg-opacity-50 backdrop-blur-sm overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-200 justify-center items-center w-full md:inset-0 h-full"
>
    <div class="relative mx-auto p-4 w-full max-w-lg h-full md:h-auto md:mt-16 lg:mt-32">
        <div class="relative p-4 bg-white rounded-lg shadow sm:p-5">
            <div class="flex justify-between rounded-t px-1 pt-1">
                <p class="text-lg mt-1 ml-2 font-semibold text-secondary select-all">
                    Код авторизации
                </p>

                <button id="closeEnterCodeButton" type="button" data-dismiss="modal"
                        class="close text-secondary-dark bg-transparent hover:bg-slate-100 hover:text-secondary rounded-lg text-sm p-1.5 inline-flex transition duration-200">
                    <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="max-w-lg container mx-auto">
                <div class="flex justify-center">
                    <form name="verifyCode" class="mx-auto" data-action="{{ route('auth.verifyCode') }}" data-method="POST">
                        @csrf

                        <p class="mt-6 mb-2 text-md text-center text-secondary">Введите код авторизации из полученной СМС</p>

                        <div class="flex justify-center space-x-2">
                            <div>
                                <input name="code-1" type="text" maxlength="1" onkeyup="inputCodeFocusNextInput(this, 'code-1', 'code-2');" id="code-1" class="block w-9 h-9 py-3 text-sm font-extrabold text-center border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                            </div>
                            <div>
                                <input name="code-2" type="text" maxlength="1" onkeyup="inputCodeFocusNextInput(this, 'code-1', 'code-3');" id="code-2" class="block w-9 h-9 py-3 text-sm font-extrabold text-center border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                            </div>
                            <div>
                                <input name="code-3" type="text" maxlength="1" onkeyup="inputCodeFocusNextInput(this, 'code-2', 'code-4');" id="code-3" class="block w-9 h-9 py-3 text-sm font-extrabold text-center border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                            </div>
                            <div>
                                <input name="code-4" type="text" maxlength="1" onkeyup="inputCodeFocusNextInput(this, 'code-3', 'code-5');" id="code-4" class="block w-9 h-9 py-3 text-sm font-extrabold text-center border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                            </div>
                            <div>
                                <input name="code-5" type="text" maxlength="1" onkeyup="inputCodeFocusNextInput(this, 'code-4', 'code-6');" id="code-5" class="block w-9 h-9 py-3 text-sm font-extrabold text-center border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                            </div>
                            <div>
                                <input name="code-6" type="text" maxlength="1" onkeyup="inputCodeFocusNextInput(this, 'code-5', 'code-6');" id="code-6" class="block w-9 h-9 py-3 text-sm font-extrabold text-center border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500" required>
                            </div>
                        </div>

                        <p id="failedVerifyCode" class="mt-2 text-sm text-center text-primary"></p>

                        <button id="verifyCodeSubmit"
                                type="submit"
                                class="mt-6 w-full rounded-lg bg-primary hover:bg-primary-dark px-4 py-2 text-center text-base font-semibold text-white shadow-md ring-gray-500 ring-offset-2 transition focus:ring-2"
                        >
                            Проверить код
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function inputCodeFocusNextInput(el, prevId, nextId) {
        if (el.value.length === 0) {
            document.getElementById(prevId).focus();
        } else {
            document.getElementById(nextId).focus();
        }
    }
</script>
