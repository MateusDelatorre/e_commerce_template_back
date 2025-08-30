package com.example.demo.domain.entities.user;

import java.util.List;
import java.util.Optional;

public interface IUserRepository {
    User save(User user);
    boolean delete(Long id);
    Optional<User> findById(Long id);
    List<User> searchByName(String nameFragment);
    List<User> searchByEmail(String emailFragment);
}
