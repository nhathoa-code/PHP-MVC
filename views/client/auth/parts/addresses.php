<div class="user-title">Sổ địa chỉ</div>
<div class="col-12 col-lg-8">
    <div class="list-address">
        <?php foreach($data['addresses'] as $item): $address = unserialize($item->meta_value);?>
            <div class="address-item mb-4">
                <div class="d-flex mb-2">
                    <div class="op d-flex align-items-center flex-grow-1">
                        <div class="fullname"><?php echo $address['name'] ?></div>
                        <div class="vr mx-1"></div>
                        <div class="phone"><?php echo $address['phone'] ?></div>
                    </div>
                    <div class="op">
                        <a
                        href="<?php echo url("user/address/edit?id={$item->id}") ?>"
                        aria-label="Link"
                        class="link"
                        >Thay đổi</a
                        >
                    </div>
                </div>
                <div class="op">
                    <div class="address"><?php echo "{$address['address']}, {$address['ward']}, {$address['district']}, {$address['province']}" ?></div>
                </div>
                <?php if(array_key_exists("default",$address)): ?>
                <div class="op mt-2"> 
                    <div class="ar-status">Địa chỉ mặc định</div>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="<?php echo url("user/address/add") ?>" aria-label="Link" class="btn-link new-address">
        <span>Thêm địa chỉ mới</span>
        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <mask id="path-1-inside-1_2705_18594" fill="white">
                <path d="M8.49998 1.33301C8.35332 1.33301 8.20798 1.33767 8.06398 1.34701L8.11732 2.17901C8.37216 2.16253 8.62781 2.16253 8.88265 2.17901L8.93598 1.34701C8.79084 1.33765 8.64543 1.33298 8.49998 1.33301ZM7.19998 1.45967C6.91132 1.51701 6.62998 1.59301 6.35665 1.68434L6.62398 2.47434C6.86489 2.3923 7.11099 2.32638 7.36065 2.27701L7.19998 1.45967ZM10.6433 1.68567C10.3677 1.59213 10.0862 1.51685 9.80065 1.46034L9.63932 2.27701C9.89065 2.32701 10.1367 2.39367 10.376 2.47434L10.6427 1.68567H10.6433ZM12.204 2.45634C11.9622 2.29447 11.7101 2.14861 11.4493 2.01967L11.08 2.76634C11.3093 2.87967 11.53 3.00767 11.7407 3.14901L12.204 2.45634ZM5.54998 2.01967C5.28931 2.14846 5.03742 2.29434 4.79598 2.45634L5.25932 3.14834C5.47065 3.00634 5.69132 2.87901 5.91932 2.76634L5.54998 2.01967ZM4.10398 2.98701C3.88598 3.17901 3.67932 3.38567 3.48732 3.60367L4.11398 4.15367C4.28265 3.96234 4.46265 3.78234 4.65398 3.61367L4.10398 2.98701ZM13.5127 3.60367C13.3207 3.38496 13.1147 3.17896 12.896 2.98701L12.346 3.61367C12.5373 3.78234 12.718 3.96234 12.886 4.15367L13.5127 3.60367ZM14.4793 5.04967C14.3506 4.78909 14.205 4.53722 14.0433 4.29567L13.3513 4.75901C13.4927 4.96967 13.62 5.19034 13.7333 5.41967L14.48 5.05034L14.4793 5.04967ZM2.95732 4.29567C2.79476 4.537 2.64887 4.78914 2.52065 5.05034L3.26732 5.41967C3.38065 5.19034 3.50865 4.96967 3.64998 4.75901L2.95665 4.29567H2.95732ZM2.18598 5.85634C2.09265 6.13167 2.01732 6.41367 1.96065 6.69901L2.77732 6.86034C2.82708 6.61077 2.893 6.3647 2.97465 6.12367L2.18598 5.85634ZM15.0393 6.69967C14.983 6.41393 14.908 6.13221 14.8147 5.85634L14.0247 6.12367C14.1067 6.36301 14.1727 6.60901 14.2227 6.86034L15.0393 6.69901V6.69967ZM1.84798 7.56367C1.82932 7.85434 1.82932 8.14501 1.84798 8.43567L2.67998 8.38234C2.6635 8.1275 2.6635 7.87185 2.67998 7.61701L1.84798 7.56367ZM15.1667 7.99967C15.1667 7.85434 15.162 7.70901 15.1527 7.56367L14.3207 7.61701C14.3373 7.87167 14.3373 8.12767 14.3207 8.38234L15.1527 8.43567C15.1618 8.29052 15.1669 8.14511 15.1667 7.99967ZM1.95998 9.29967C2.01732 9.58834 2.09332 9.86967 2.18465 10.143L2.97465 9.87567C2.89275 9.63472 2.82683 9.38863 2.77732 9.13901L1.96065 9.30034L1.95998 9.29967ZM14.814 10.143C14.9073 9.86967 14.9827 9.58834 15.0393 9.30034L14.2227 9.13901C14.1726 9.38859 14.1065 9.63466 14.0247 9.87567L14.814 10.143ZM2.51998 10.9497C2.64865 11.2103 2.79465 11.4623 2.95665 11.7037L3.64798 11.2403C3.50658 11.0287 3.37919 10.808 3.26665 10.5797L2.51998 10.9497ZM14.0433 11.7037C14.2047 11.463 14.3513 11.211 14.48 10.949L13.7333 10.5797C13.6202 10.8079 13.4924 11.0286 13.3507 11.2403L14.0433 11.7037ZM3.48665 12.3957C3.67865 12.6137 3.88532 12.8203 4.10332 13.0123L4.65332 12.3857C4.46178 12.2176 4.2814 12.0372 4.11332 11.8457L3.48665 12.3957ZM12.8953 13.0123C13.1133 12.8203 13.32 12.6137 13.512 12.3957L12.8853 11.8457C12.7176 12.0376 12.5372 12.218 12.3453 12.3857L12.8953 13.0123ZM11.4487 13.9797C11.7096 13.851 11.9617 13.7051 12.2033 13.543L11.74 12.851C11.5283 12.9925 11.3076 13.1201 11.0793 13.233L11.4487 13.9797ZM4.79532 13.543C5.03532 13.7043 5.28732 13.851 5.54998 13.9797L5.91932 13.233C5.69099 13.12 5.47029 12.9922 5.25865 12.8503L4.79532 13.543ZM6.35532 14.3137C6.62865 14.407 6.91065 14.4823 7.19865 14.539L7.35998 13.7223C7.11036 13.6728 6.86427 13.6069 6.62332 13.525L6.35598 14.3137H6.35532ZM9.79932 14.5397C10.0851 14.4834 10.3668 14.4084 10.6427 14.315L10.3753 13.525C10.134 13.6063 9.88865 13.6723 9.63865 13.7223L9.79998 14.539L9.79932 14.5397ZM8.06332 14.6523C8.35332 14.671 8.64465 14.671 8.93532 14.6523L8.88198 13.8203C8.62714 13.8369 8.37149 13.8369 8.11665 13.8203L8.06332 14.6523ZM8.91598 5.08301C8.91598 4.9725 8.87209 4.86652 8.79394 4.78838C8.7158 4.71024 8.60982 4.66634 8.49932 4.66634C8.38881 4.66634 8.28283 4.71024 8.20469 4.78838C8.12655 4.86652 8.08265 4.9725 8.08265 5.08301V7.58301H5.58265C5.47214 7.58301 5.36616 7.62691 5.28802 7.70505C5.20988 7.78319 5.16598 7.88917 5.16598 7.99967C5.16598 8.11018 5.20988 8.21616 5.28802 8.2943C5.36616 8.37244 5.47214 8.41634 5.58265 8.41634H8.08265V10.9163C8.08265 11.0268 8.12655 11.1328 8.20469 11.211C8.28283 11.2891 8.38881 11.333 8.49932 11.333C8.60982 11.333 8.7158 11.2891 8.79394 11.211C8.87209 11.1328 8.91598 11.0268 8.91598 10.9163V8.41634H11.416C11.5265 8.41634 11.6325 8.37244 11.7106 8.2943C11.7888 8.21616 11.8327 8.11018 11.8327 7.99967C11.8327 7.88917 11.7888 7.78319 11.7106 7.70505C11.6325 7.62691 11.5265 7.58301 11.416 7.58301H8.91598V5.08301Z"></path>
            </mask>
            <path d="M8.49998 1.33301C8.35332 1.33301 8.20798 1.33767 8.06398 1.34701L8.11732 2.17901C8.37216 2.16253 8.62781 2.16253 8.88265 2.17901L8.93598 1.34701C8.79084 1.33765 8.64543 1.33298 8.49998 1.33301ZM7.19998 1.45967C6.91132 1.51701 6.62998 1.59301 6.35665 1.68434L6.62398 2.47434C6.86489 2.3923 7.11099 2.32638 7.36065 2.27701L7.19998 1.45967ZM10.6433 1.68567C10.3677 1.59213 10.0862 1.51685 9.80065 1.46034L9.63932 2.27701C9.89065 2.32701 10.1367 2.39367 10.376 2.47434L10.6427 1.68567H10.6433ZM12.204 2.45634C11.9622 2.29447 11.7101 2.14861 11.4493 2.01967L11.08 2.76634C11.3093 2.87967 11.53 3.00767 11.7407 3.14901L12.204 2.45634ZM5.54998 2.01967C5.28931 2.14846 5.03742 2.29434 4.79598 2.45634L5.25932 3.14834C5.47065 3.00634 5.69132 2.87901 5.91932 2.76634L5.54998 2.01967ZM4.10398 2.98701C3.88598 3.17901 3.67932 3.38567 3.48732 3.60367L4.11398 4.15367C4.28265 3.96234 4.46265 3.78234 4.65398 3.61367L4.10398 2.98701ZM13.5127 3.60367C13.3207 3.38496 13.1147 3.17896 12.896 2.98701L12.346 3.61367C12.5373 3.78234 12.718 3.96234 12.886 4.15367L13.5127 3.60367ZM14.4793 5.04967C14.3506 4.78909 14.205 4.53722 14.0433 4.29567L13.3513 4.75901C13.4927 4.96967 13.62 5.19034 13.7333 5.41967L14.48 5.05034L14.4793 5.04967ZM2.95732 4.29567C2.79476 4.537 2.64887 4.78914 2.52065 5.05034L3.26732 5.41967C3.38065 5.19034 3.50865 4.96967 3.64998 4.75901L2.95665 4.29567H2.95732ZM2.18598 5.85634C2.09265 6.13167 2.01732 6.41367 1.96065 6.69901L2.77732 6.86034C2.82708 6.61077 2.893 6.3647 2.97465 6.12367L2.18598 5.85634ZM15.0393 6.69967C14.983 6.41393 14.908 6.13221 14.8147 5.85634L14.0247 6.12367C14.1067 6.36301 14.1727 6.60901 14.2227 6.86034L15.0393 6.69901V6.69967ZM1.84798 7.56367C1.82932 7.85434 1.82932 8.14501 1.84798 8.43567L2.67998 8.38234C2.6635 8.1275 2.6635 7.87185 2.67998 7.61701L1.84798 7.56367ZM15.1667 7.99967C15.1667 7.85434 15.162 7.70901 15.1527 7.56367L14.3207 7.61701C14.3373 7.87167 14.3373 8.12767 14.3207 8.38234L15.1527 8.43567C15.1618 8.29052 15.1669 8.14511 15.1667 7.99967ZM1.95998 9.29967C2.01732 9.58834 2.09332 9.86967 2.18465 10.143L2.97465 9.87567C2.89275 9.63472 2.82683 9.38863 2.77732 9.13901L1.96065 9.30034L1.95998 9.29967ZM14.814 10.143C14.9073 9.86967 14.9827 9.58834 15.0393 9.30034L14.2227 9.13901C14.1726 9.38859 14.1065 9.63466 14.0247 9.87567L14.814 10.143ZM2.51998 10.9497C2.64865 11.2103 2.79465 11.4623 2.95665 11.7037L3.64798 11.2403C3.50658 11.0287 3.37919 10.808 3.26665 10.5797L2.51998 10.9497ZM14.0433 11.7037C14.2047 11.463 14.3513 11.211 14.48 10.949L13.7333 10.5797C13.6202 10.8079 13.4924 11.0286 13.3507 11.2403L14.0433 11.7037ZM3.48665 12.3957C3.67865 12.6137 3.88532 12.8203 4.10332 13.0123L4.65332 12.3857C4.46178 12.2176 4.2814 12.0372 4.11332 11.8457L3.48665 12.3957ZM12.8953 13.0123C13.1133 12.8203 13.32 12.6137 13.512 12.3957L12.8853 11.8457C12.7176 12.0376 12.5372 12.218 12.3453 12.3857L12.8953 13.0123ZM11.4487 13.9797C11.7096 13.851 11.9617 13.7051 12.2033 13.543L11.74 12.851C11.5283 12.9925 11.3076 13.1201 11.0793 13.233L11.4487 13.9797ZM4.79532 13.543C5.03532 13.7043 5.28732 13.851 5.54998 13.9797L5.91932 13.233C5.69099 13.12 5.47029 12.9922 5.25865 12.8503L4.79532 13.543ZM6.35532 14.3137C6.62865 14.407 6.91065 14.4823 7.19865 14.539L7.35998 13.7223C7.11036 13.6728 6.86427 13.6069 6.62332 13.525L6.35598 14.3137H6.35532ZM9.79932 14.5397C10.0851 14.4834 10.3668 14.4084 10.6427 14.315L10.3753 13.525C10.134 13.6063 9.88865 13.6723 9.63865 13.7223L9.79998 14.539L9.79932 14.5397ZM8.06332 14.6523C8.35332 14.671 8.64465 14.671 8.93532 14.6523L8.88198 13.8203C8.62714 13.8369 8.37149 13.8369 8.11665 13.8203L8.06332 14.6523ZM8.91598 5.08301C8.91598 4.9725 8.87209 4.86652 8.79394 4.78838C8.7158 4.71024 8.60982 4.66634 8.49932 4.66634C8.38881 4.66634 8.28283 4.71024 8.20469 4.78838C8.12655 4.86652 8.08265 4.9725 8.08265 5.08301V7.58301H5.58265C5.47214 7.58301 5.36616 7.62691 5.28802 7.70505C5.20988 7.78319 5.16598 7.88917 5.16598 7.99967C5.16598 8.11018 5.20988 8.21616 5.28802 8.2943C5.36616 8.37244 5.47214 8.41634 5.58265 8.41634H8.08265V10.9163C8.08265 11.0268 8.12655 11.1328 8.20469 11.211C8.28283 11.2891 8.38881 11.333 8.49932 11.333C8.60982 11.333 8.7158 11.2891 8.79394 11.211C8.87209 11.1328 8.91598 11.0268 8.91598 10.9163V8.41634H11.416C11.5265 8.41634 11.6325 8.37244 11.7106 8.2943C11.7888 8.21616 11.8327 8.11018 11.8327 7.99967C11.8327 7.88917 11.7888 7.78319 11.7106 7.70505C11.6325 7.62691 11.5265 7.58301 11.416 7.58301H8.91598V5.08301Z" fill="#25282B"></path>
            <path d="M8.49998 1.33301C8.35332 1.33301 8.20798 1.33767 8.06398 1.34701L8.11732 2.17901C8.37216 2.16253 8.62781 2.16253 8.88265 2.17901L8.93598 1.34701C8.79084 1.33765 8.64543 1.33298 8.49998 1.33301ZM7.19998 1.45967C6.91132 1.51701 6.62998 1.59301 6.35665 1.68434L6.62398 2.47434C6.86489 2.3923 7.11099 2.32638 7.36065 2.27701L7.19998 1.45967ZM10.6433 1.68567C10.3677 1.59213 10.0862 1.51685 9.80065 1.46034L9.63932 2.27701C9.89065 2.32701 10.1367 2.39367 10.376 2.47434L10.6427 1.68567H10.6433ZM12.204 2.45634C11.9622 2.29447 11.7101 2.14861 11.4493 2.01967L11.08 2.76634C11.3093 2.87967 11.53 3.00767 11.7407 3.14901L12.204 2.45634ZM5.54998 2.01967C5.28931 2.14846 5.03742 2.29434 4.79598 2.45634L5.25932 3.14834C5.47065 3.00634 5.69132 2.87901 5.91932 2.76634L5.54998 2.01967ZM4.10398 2.98701C3.88598 3.17901 3.67932 3.38567 3.48732 3.60367L4.11398 4.15367C4.28265 3.96234 4.46265 3.78234 4.65398 3.61367L4.10398 2.98701ZM13.5127 3.60367C13.3207 3.38496 13.1147 3.17896 12.896 2.98701L12.346 3.61367C12.5373 3.78234 12.718 3.96234 12.886 4.15367L13.5127 3.60367ZM14.4793 5.04967C14.3506 4.78909 14.205 4.53722 14.0433 4.29567L13.3513 4.75901C13.4927 4.96967 13.62 5.19034 13.7333 5.41967L14.48 5.05034L14.4793 5.04967ZM2.95732 4.29567C2.79476 4.537 2.64887 4.78914 2.52065 5.05034L3.26732 5.41967C3.38065 5.19034 3.50865 4.96967 3.64998 4.75901L2.95665 4.29567H2.95732ZM2.18598 5.85634C2.09265 6.13167 2.01732 6.41367 1.96065 6.69901L2.77732 6.86034C2.82708 6.61077 2.893 6.3647 2.97465 6.12367L2.18598 5.85634ZM15.0393 6.69967C14.983 6.41393 14.908 6.13221 14.8147 5.85634L14.0247 6.12367C14.1067 6.36301 14.1727 6.60901 14.2227 6.86034L15.0393 6.69901V6.69967ZM1.84798 7.56367C1.82932 7.85434 1.82932 8.14501 1.84798 8.43567L2.67998 8.38234C2.6635 8.1275 2.6635 7.87185 2.67998 7.61701L1.84798 7.56367ZM15.1667 7.99967C15.1667 7.85434 15.162 7.70901 15.1527 7.56367L14.3207 7.61701C14.3373 7.87167 14.3373 8.12767 14.3207 8.38234L15.1527 8.43567C15.1618 8.29052 15.1669 8.14511 15.1667 7.99967ZM1.95998 9.29967C2.01732 9.58834 2.09332 9.86967 2.18465 10.143L2.97465 9.87567C2.89275 9.63472 2.82683 9.38863 2.77732 9.13901L1.96065 9.30034L1.95998 9.29967ZM14.814 10.143C14.9073 9.86967 14.9827 9.58834 15.0393 9.30034L14.2227 9.13901C14.1726 9.38859 14.1065 9.63466 14.0247 9.87567L14.814 10.143ZM2.51998 10.9497C2.64865 11.2103 2.79465 11.4623 2.95665 11.7037L3.64798 11.2403C3.50658 11.0287 3.37919 10.808 3.26665 10.5797L2.51998 10.9497ZM14.0433 11.7037C14.2047 11.463 14.3513 11.211 14.48 10.949L13.7333 10.5797C13.6202 10.8079 13.4924 11.0286 13.3507 11.2403L14.0433 11.7037ZM3.48665 12.3957C3.67865 12.6137 3.88532 12.8203 4.10332 13.0123L4.65332 12.3857C4.46178 12.2176 4.2814 12.0372 4.11332 11.8457L3.48665 12.3957ZM12.8953 13.0123C13.1133 12.8203 13.32 12.6137 13.512 12.3957L12.8853 11.8457C12.7176 12.0376 12.5372 12.218 12.3453 12.3857L12.8953 13.0123ZM11.4487 13.9797C11.7096 13.851 11.9617 13.7051 12.2033 13.543L11.74 12.851C11.5283 12.9925 11.3076 13.1201 11.0793 13.233L11.4487 13.9797ZM4.79532 13.543C5.03532 13.7043 5.28732 13.851 5.54998 13.9797L5.91932 13.233C5.69099 13.12 5.47029 12.9922 5.25865 12.8503L4.79532 13.543ZM6.35532 14.3137C6.62865 14.407 6.91065 14.4823 7.19865 14.539L7.35998 13.7223C7.11036 13.6728 6.86427 13.6069 6.62332 13.525L6.35598 14.3137H6.35532ZM9.79932 14.5397C10.0851 14.4834 10.3668 14.4084 10.6427 14.315L10.3753 13.525C10.134 13.6063 9.88865 13.6723 9.63865 13.7223L9.79998 14.539L9.79932 14.5397ZM8.06332 14.6523C8.35332 14.671 8.64465 14.671 8.93532 14.6523L8.88198 13.8203C8.62714 13.8369 8.37149 13.8369 8.11665 13.8203L8.06332 14.6523ZM8.91598 5.08301C8.91598 4.9725 8.87209 4.86652 8.79394 4.78838C8.7158 4.71024 8.60982 4.66634 8.49932 4.66634C8.38881 4.66634 8.28283 4.71024 8.20469 4.78838C8.12655 4.86652 8.08265 4.9725 8.08265 5.08301V7.58301H5.58265C5.47214 7.58301 5.36616 7.62691 5.28802 7.70505C5.20988 7.78319 5.16598 7.88917 5.16598 7.99967C5.16598 8.11018 5.20988 8.21616 5.28802 8.2943C5.36616 8.37244 5.47214 8.41634 5.58265 8.41634H8.08265V10.9163C8.08265 11.0268 8.12655 11.1328 8.20469 11.211C8.28283 11.2891 8.38881 11.333 8.49932 11.333C8.60982 11.333 8.7158 11.2891 8.79394 11.211C8.87209 11.1328 8.91598 11.0268 8.91598 10.9163V8.41634H11.416C11.5265 8.41634 11.6325 8.37244 11.7106 8.2943C11.7888 8.21616 11.8327 8.11018 11.8327 7.99967C11.8327 7.88917 11.7888 7.78319 11.7106 7.70505C11.6325 7.62691 11.5265 7.58301 11.416 7.58301H8.91598V5.08301Z" fill="black" fill-opacity="0.2"></path>
            <path d="M8.49998 1.33301C8.35332 1.33301 8.20798 1.33767 8.06398 1.34701L8.11732 2.17901C8.37216 2.16253 8.62781 2.16253 8.88265 2.17901L8.93598 1.34701C8.79084 1.33765 8.64543 1.33298 8.49998 1.33301ZM7.19998 1.45967C6.91132 1.51701 6.62998 1.59301 6.35665 1.68434L6.62398 2.47434C6.86489 2.3923 7.11099 2.32638 7.36065 2.27701L7.19998 1.45967ZM10.6433 1.68567C10.3677 1.59213 10.0862 1.51685 9.80065 1.46034L9.63932 2.27701C9.89065 2.32701 10.1367 2.39367 10.376 2.47434L10.6427 1.68567H10.6433ZM12.204 2.45634C11.9622 2.29447 11.7101 2.14861 11.4493 2.01967L11.08 2.76634C11.3093 2.87967 11.53 3.00767 11.7407 3.14901L12.204 2.45634ZM5.54998 2.01967C5.28931 2.14846 5.03742 2.29434 4.79598 2.45634L5.25932 3.14834C5.47065 3.00634 5.69132 2.87901 5.91932 2.76634L5.54998 2.01967ZM4.10398 2.98701C3.88598 3.17901 3.67932 3.38567 3.48732 3.60367L4.11398 4.15367C4.28265 3.96234 4.46265 3.78234 4.65398 3.61367L4.10398 2.98701ZM13.5127 3.60367C13.3207 3.38496 13.1147 3.17896 12.896 2.98701L12.346 3.61367C12.5373 3.78234 12.718 3.96234 12.886 4.15367L13.5127 3.60367ZM14.4793 5.04967C14.3506 4.78909 14.205 4.53722 14.0433 4.29567L13.3513 4.75901C13.4927 4.96967 13.62 5.19034 13.7333 5.41967L14.48 5.05034L14.4793 5.04967ZM2.95732 4.29567C2.79476 4.537 2.64887 4.78914 2.52065 5.05034L3.26732 5.41967C3.38065 5.19034 3.50865 4.96967 3.64998 4.75901L2.95665 4.29567H2.95732ZM2.18598 5.85634C2.09265 6.13167 2.01732 6.41367 1.96065 6.69901L2.77732 6.86034C2.82708 6.61077 2.893 6.3647 2.97465 6.12367L2.18598 5.85634ZM15.0393 6.69967C14.983 6.41393 14.908 6.13221 14.8147 5.85634L14.0247 6.12367C14.1067 6.36301 14.1727 6.60901 14.2227 6.86034L15.0393 6.69901V6.69967ZM1.84798 7.56367C1.82932 7.85434 1.82932 8.14501 1.84798 8.43567L2.67998 8.38234C2.6635 8.1275 2.6635 7.87185 2.67998 7.61701L1.84798 7.56367ZM15.1667 7.99967C15.1667 7.85434 15.162 7.70901 15.1527 7.56367L14.3207 7.61701C14.3373 7.87167 14.3373 8.12767 14.3207 8.38234L15.1527 8.43567C15.1618 8.29052 15.1669 8.14511 15.1667 7.99967ZM1.95998 9.29967C2.01732 9.58834 2.09332 9.86967 2.18465 10.143L2.97465 9.87567C2.89275 9.63472 2.82683 9.38863 2.77732 9.13901L1.96065 9.30034L1.95998 9.29967ZM14.814 10.143C14.9073 9.86967 14.9827 9.58834 15.0393 9.30034L14.2227 9.13901C14.1726 9.38859 14.1065 9.63466 14.0247 9.87567L14.814 10.143ZM2.51998 10.9497C2.64865 11.2103 2.79465 11.4623 2.95665 11.7037L3.64798 11.2403C3.50658 11.0287 3.37919 10.808 3.26665 10.5797L2.51998 10.9497ZM14.0433 11.7037C14.2047 11.463 14.3513 11.211 14.48 10.949L13.7333 10.5797C13.6202 10.8079 13.4924 11.0286 13.3507 11.2403L14.0433 11.7037ZM3.48665 12.3957C3.67865 12.6137 3.88532 12.8203 4.10332 13.0123L4.65332 12.3857C4.46178 12.2176 4.2814 12.0372 4.11332 11.8457L3.48665 12.3957ZM12.8953 13.0123C13.1133 12.8203 13.32 12.6137 13.512 12.3957L12.8853 11.8457C12.7176 12.0376 12.5372 12.218 12.3453 12.3857L12.8953 13.0123ZM11.4487 13.9797C11.7096 13.851 11.9617 13.7051 12.2033 13.543L11.74 12.851C11.5283 12.9925 11.3076 13.1201 11.0793 13.233L11.4487 13.9797ZM4.79532 13.543C5.03532 13.7043 5.28732 13.851 5.54998 13.9797L5.91932 13.233C5.69099 13.12 5.47029 12.9922 5.25865 12.8503L4.79532 13.543ZM6.35532 14.3137C6.62865 14.407 6.91065 14.4823 7.19865 14.539L7.35998 13.7223C7.11036 13.6728 6.86427 13.6069 6.62332 13.525L6.35598 14.3137H6.35532ZM9.79932 14.5397C10.0851 14.4834 10.3668 14.4084 10.6427 14.315L10.3753 13.525C10.134 13.6063 9.88865 13.6723 9.63865 13.7223L9.79998 14.539L9.79932 14.5397ZM8.06332 14.6523C8.35332 14.671 8.64465 14.671 8.93532 14.6523L8.88198 13.8203C8.62714 13.8369 8.37149 13.8369 8.11665 13.8203L8.06332 14.6523ZM8.91598 5.08301C8.91598 4.9725 8.87209 4.86652 8.79394 4.78838C8.7158 4.71024 8.60982 4.66634 8.49932 4.66634C8.38881 4.66634 8.28283 4.71024 8.20469 4.78838C8.12655 4.86652 8.08265 4.9725 8.08265 5.08301V7.58301H5.58265C5.47214 7.58301 5.36616 7.62691 5.28802 7.70505C5.20988 7.78319 5.16598 7.88917 5.16598 7.99967C5.16598 8.11018 5.20988 8.21616 5.28802 8.2943C5.36616 8.37244 5.47214 8.41634 5.58265 8.41634H8.08265V10.9163C8.08265 11.0268 8.12655 11.1328 8.20469 11.211C8.28283 11.2891 8.38881 11.333 8.49932 11.333C8.60982 11.333 8.7158 11.2891 8.79394 11.211C8.87209 11.1328 8.91598 11.0268 8.91598 10.9163V8.41634H11.416C11.5265 8.41634 11.6325 8.37244 11.7106 8.2943C11.7888 8.21616 11.8327 8.11018 11.8327 7.99967C11.8327 7.88917 11.7888 7.78319 11.7106 7.70505C11.6325 7.62691 11.5265 7.58301 11.416 7.58301H8.91598V5.08301Z" stroke="#25282B" stroke-width="3.83333" mask="url(#path-1-inside-1_2705_18594)"></path>
            <path d="M8.49998 1.33301C8.35332 1.33301 8.20798 1.33767 8.06398 1.34701L8.11732 2.17901C8.37216 2.16253 8.62781 2.16253 8.88265 2.17901L8.93598 1.34701C8.79084 1.33765 8.64543 1.33298 8.49998 1.33301ZM7.19998 1.45967C6.91132 1.51701 6.62998 1.59301 6.35665 1.68434L6.62398 2.47434C6.86489 2.3923 7.11099 2.32638 7.36065 2.27701L7.19998 1.45967ZM10.6433 1.68567C10.3677 1.59213 10.0862 1.51685 9.80065 1.46034L9.63932 2.27701C9.89065 2.32701 10.1367 2.39367 10.376 2.47434L10.6427 1.68567H10.6433ZM12.204 2.45634C11.9622 2.29447 11.7101 2.14861 11.4493 2.01967L11.08 2.76634C11.3093 2.87967 11.53 3.00767 11.7407 3.14901L12.204 2.45634ZM5.54998 2.01967C5.28931 2.14846 5.03742 2.29434 4.79598 2.45634L5.25932 3.14834C5.47065 3.00634 5.69132 2.87901 5.91932 2.76634L5.54998 2.01967ZM4.10398 2.98701C3.88598 3.17901 3.67932 3.38567 3.48732 3.60367L4.11398 4.15367C4.28265 3.96234 4.46265 3.78234 4.65398 3.61367L4.10398 2.98701ZM13.5127 3.60367C13.3207 3.38496 13.1147 3.17896 12.896 2.98701L12.346 3.61367C12.5373 3.78234 12.718 3.96234 12.886 4.15367L13.5127 3.60367ZM14.4793 5.04967C14.3506 4.78909 14.205 4.53722 14.0433 4.29567L13.3513 4.75901C13.4927 4.96967 13.62 5.19034 13.7333 5.41967L14.48 5.05034L14.4793 5.04967ZM2.95732 4.29567C2.79476 4.537 2.64887 4.78914 2.52065 5.05034L3.26732 5.41967C3.38065 5.19034 3.50865 4.96967 3.64998 4.75901L2.95665 4.29567H2.95732ZM2.18598 5.85634C2.09265 6.13167 2.01732 6.41367 1.96065 6.69901L2.77732 6.86034C2.82708 6.61077 2.893 6.3647 2.97465 6.12367L2.18598 5.85634ZM15.0393 6.69967C14.983 6.41393 14.908 6.13221 14.8147 5.85634L14.0247 6.12367C14.1067 6.36301 14.1727 6.60901 14.2227 6.86034L15.0393 6.69901V6.69967ZM1.84798 7.56367C1.82932 7.85434 1.82932 8.14501 1.84798 8.43567L2.67998 8.38234C2.6635 8.1275 2.6635 7.87185 2.67998 7.61701L1.84798 7.56367ZM15.1667 7.99967C15.1667 7.85434 15.162 7.70901 15.1527 7.56367L14.3207 7.61701C14.3373 7.87167 14.3373 8.12767 14.3207 8.38234L15.1527 8.43567C15.1618 8.29052 15.1669 8.14511 15.1667 7.99967ZM1.95998 9.29967C2.01732 9.58834 2.09332 9.86967 2.18465 10.143L2.97465 9.87567C2.89275 9.63472 2.82683 9.38863 2.77732 9.13901L1.96065 9.30034L1.95998 9.29967ZM14.814 10.143C14.9073 9.86967 14.9827 9.58834 15.0393 9.30034L14.2227 9.13901C14.1726 9.38859 14.1065 9.63466 14.0247 9.87567L14.814 10.143ZM2.51998 10.9497C2.64865 11.2103 2.79465 11.4623 2.95665 11.7037L3.64798 11.2403C3.50658 11.0287 3.37919 10.808 3.26665 10.5797L2.51998 10.9497ZM14.0433 11.7037C14.2047 11.463 14.3513 11.211 14.48 10.949L13.7333 10.5797C13.6202 10.8079 13.4924 11.0286 13.3507 11.2403L14.0433 11.7037ZM3.48665 12.3957C3.67865 12.6137 3.88532 12.8203 4.10332 13.0123L4.65332 12.3857C4.46178 12.2176 4.2814 12.0372 4.11332 11.8457L3.48665 12.3957ZM12.8953 13.0123C13.1133 12.8203 13.32 12.6137 13.512 12.3957L12.8853 11.8457C12.7176 12.0376 12.5372 12.218 12.3453 12.3857L12.8953 13.0123ZM11.4487 13.9797C11.7096 13.851 11.9617 13.7051 12.2033 13.543L11.74 12.851C11.5283 12.9925 11.3076 13.1201 11.0793 13.233L11.4487 13.9797ZM4.79532 13.543C5.03532 13.7043 5.28732 13.851 5.54998 13.9797L5.91932 13.233C5.69099 13.12 5.47029 12.9922 5.25865 12.8503L4.79532 13.543ZM6.35532 14.3137C6.62865 14.407 6.91065 14.4823 7.19865 14.539L7.35998 13.7223C7.11036 13.6728 6.86427 13.6069 6.62332 13.525L6.35598 14.3137H6.35532ZM9.79932 14.5397C10.0851 14.4834 10.3668 14.4084 10.6427 14.315L10.3753 13.525C10.134 13.6063 9.88865 13.6723 9.63865 13.7223L9.79998 14.539L9.79932 14.5397ZM8.06332 14.6523C8.35332 14.671 8.64465 14.671 8.93532 14.6523L8.88198 13.8203C8.62714 13.8369 8.37149 13.8369 8.11665 13.8203L8.06332 14.6523ZM8.91598 5.08301C8.91598 4.9725 8.87209 4.86652 8.79394 4.78838C8.7158 4.71024 8.60982 4.66634 8.49932 4.66634C8.38881 4.66634 8.28283 4.71024 8.20469 4.78838C8.12655 4.86652 8.08265 4.9725 8.08265 5.08301V7.58301H5.58265C5.47214 7.58301 5.36616 7.62691 5.28802 7.70505C5.20988 7.78319 5.16598 7.88917 5.16598 7.99967C5.16598 8.11018 5.20988 8.21616 5.28802 8.2943C5.36616 8.37244 5.47214 8.41634 5.58265 8.41634H8.08265V10.9163C8.08265 11.0268 8.12655 11.1328 8.20469 11.211C8.28283 11.2891 8.38881 11.333 8.49932 11.333C8.60982 11.333 8.7158 11.2891 8.79394 11.211C8.87209 11.1328 8.91598 11.0268 8.91598 10.9163V8.41634H11.416C11.5265 8.41634 11.6325 8.37244 11.7106 8.2943C11.7888 8.21616 11.8327 8.11018 11.8327 7.99967C11.8327 7.88917 11.7888 7.78319 11.7106 7.70505C11.6325 7.62691 11.5265 7.58301 11.416 7.58301H8.91598V5.08301Z" stroke="black" stroke-opacity="0.2" stroke-width="3.83333" mask="url(#path-1-inside-1_2705_18594)"></path>
        </svg>
    </a>
</div>
